<?php

/**
 * This file is a part of Core - set of reusable tools and code.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Admin\Controller;


use XF\Admin\Controller\AbstractController;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Structure;
use XF\Mvc\ParameterBag;

abstract class AbstractCrudController extends AbstractController
{
    /**
     * @var Structure
     */
    protected $structure;

    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->structure = $this->em()->getEntityStructure($this->_entityName());
    }

    public function actionIndex(ParameterBag $params)
    {
        if ($params[$this->structure->primaryKey])
        {
            return $this->rerouteController(get_class($this), 'edit', $params);
        }

        $viewParams = $this->_indexViewParams();
        list($perPage, $page) = [$viewParams['perPage'], $viewParams['page']];

        $filter = $this->filter('_xfFilter', [
            'text' => 'str',
            'prefix' => 'bool'
        ]);

        $finder = $this->getFinder()->limitByPage($page, $perPage);
        $this->matchByFilter($finder, $filter);

        $viewParams += [
            'filter'    => $filter['text'],
            'total'     => $finder->total(),
            'entities'  => $finder->fetch()
        ];

        return $this->view($this->_viewName('Index'), $this->_templateName('index'), $viewParams);
    }

    public function actionAdd(ParameterBag $params)
    {
        $entity = $this->em()->create($this->_entityName());
        return $this->entityAddEdit($entity);
    }

    public function actionEdit(ParameterBag $params)
    {
        $entity = $this->assertRecordExists($this->_entityName(), $params[$this->structure->primaryKey]);
        return $this->entityAddEdit($entity);
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionDelete(ParameterBag $params)
    {
        $entity = $this->assertRecordExists($this->_entityName(), $params[$this->structure->primaryKey]);
        $route = $this->_route();

        $confirmUrl = $this->buildLink($route . '/delete', $entity);
        $contentUrl = $this->buildLink($route . '/edit', $entity);
        $returnUrl = $this->buildLink($route);
        $contentTitle = $entity->get($this->_titleColumnName());

        /** @var \XF\ControllerPlugin\Delete $deletePlugIn */
        $deletePlugIn = $this->plugin('XF:Delete');
        return $deletePlugIn->actionDelete($entity, $confirmUrl, $contentUrl, $returnUrl, $contentTitle);
    }

    /**
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @throws \XF\PrintableException
     */
    public function actionSave(ParameterBag $params)
    {
        $isEdit = isset($params[$this->structure->primaryKey]);

        /** @var \XF\Mvc\Entity\Entity $entity */
        if ($isEdit)
        {
            $entity = $this->assertRecordExists($this->_entityName(), $params[$this->structure->primaryKey]);
        }
        else
        {
            $entity = $this->em()->create($this->_entityName());
        }

        $baseUrl = $this->_route();
        $this->entitySaveProcess($entity)->run();

        $redirect = $this->redirect($this->buildLink($baseUrl));
        if ($isEdit)
        {
            $redirect->setUrl($this->buildLink($baseUrl . '/edit', $entity));
        }

        return $redirect;
    }

    /**
     * @param Entity $entity
     * @return \XF\Mvc\Reply\View
     */
    protected function entityAddEdit(Entity $entity)
    {
        $route = $this->_route() . '/save';
        $viewParams = [
            'entity'    => $entity,
            'saveUrl'   => $this->buildLink($route, $entity->isInsert() ? null : $entity)
        ];

        return $this->view($this->_viewName('Edit'), $this->_templateName('edit'), $viewParams);
    }

    /**
     * @param Entity $entity
     * @return \XF\Mvc\FormAction
     */
    protected function entitySaveProcess(Entity $entity)
    {
        $formAction = $this->formAction(true);
        $formAction->basicEntitySave($entity, $this->filter($this->_editableFields($entity)));
        $formAction->validateEntity($entity);

        return $formAction;
    }

    /**
     * @param $viewName
     * @return string
     */
    public function _viewName($viewName)
    {
        return $this->_viewPrefix() . $viewName;
    }

    public function _templateName($templateName)
    {
        return $this->_templatePrefix() . '_' . $templateName;
    }

    /**
     * @return string
     */
    abstract protected function _entityName();

    /**
     * @return string
     */
    abstract protected function _route();

    /**
     * @return string
     */
    abstract protected function _titleColumnName();

    /**
     * @return string
     */
    protected function _viewPrefix()
    {
        $rootClass = $this->rootClass;
        $shortName = $this->structure->shortName;

        $rootClass = substr($rootClass, 0, strpos($rootClass, 'Admin\Controller'));
        $shortName = substr($shortName, strpos($rootClass, ':') + 1);

        return $rootClass . ':' . $shortName . '\\';
    }

    /**
     * @return string
     */
    abstract protected function _templatePrefix();

    protected function _editableFields(Entity $entity)
    {
        $structure = $this->structure;
        $editableFields = [];
        $editableFilter = $this->_editableColumnFilter();

        foreach ($structure->columns as $name => $definition)
        {
            if (!$editableFilter($definition, $entity))
            {
                continue;
            }

            $editableFields[$name] = $this->_detectColumnType($definition['type']);
        }

        return $editableFields;
    }

    /**
     * @param $type
     * @return string
     */
    protected function _detectColumnType($type)
    {
        switch ($type)
        {
            case Entity::UINT:
                return 'uint';

            case Entity::INT:
                return 'int';

            case Entity::FLOAT:
                return 'float';

            case Entity::BOOL:
                return 'bool';

            default:
                return 'str';
        }
    }

    /**
     * @return \Closure
     */
    protected function _editableColumnFilter()
    {
        return function ($column, Entity $entity)
        {
            if (array_key_exists('autoIncrement', $column) && $column['autoIncrement'])
            {
                return false;
            }

            if (array_key_exists('writeOnce', $column) && $column['writeOnce'] && !$entity->isInsert())
            {
                return false;
            }

            if (in_array($column['type'], [Entity::JSON, Entity::JSON_ARRAY, Entity::BINARY, Entity::LIST_COMMA, Entity::LIST_LINES, Entity::SERIALIZED, Entity::SERIALIZED_ARRAY]))
            {
                return false;
            }

            return true;
        };
    }

    /**
     * @return array
     */
    protected function _indexViewParams()
    {
        return [
            'page'              => $this->filterPage(),
            'perPage'           => 25,

            'searchFilterUrl'   => $this->buildLink($this->_route())
        ];
    }

    /**
     * @return \XF\Mvc\Entity\Finder
     */
    protected function getFinder()
    {
        return $this->finder($this->_entityName());
    }

    /**
     * @param Finder $finder
     * @param array $filter
     */
    protected function matchByFilter(Finder $finder, array $filter)
    {
        if ($filter['text'])
        {
            $finder->where(
                $finder->columnUtf8($this->_titleColumnName()),
                'LIKE',
                $finder->escapeLike($filter['text'], $filter['prefix'] ? '?%' : '%?%')
            );
        }
    }
}