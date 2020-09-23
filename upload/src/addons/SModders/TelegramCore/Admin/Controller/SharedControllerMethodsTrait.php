<?php


namespace SModders\TelegramCore\Admin\Controller;


use SModders\TelegramCore\Entity\Bot;
use TelegramBot\Api\Exception;
use XF\PrintableException;

trait SharedControllerMethodsTrait
{
    /**
     * @param Exception $exception
     * @param null|string $message
     * @param null|int $code
     * @return bool
     */
    protected function isConnectionExistsByException(Exception $exception, &$message = null, &$code = null)
    {
        $code = $exception->getCode();
        $message = $exception->getMessage();

        if (in_array($code, [0, 404]))
        {
            return true;
        }

        return false;
    }

    /**
     * @param \Closure $call
     * @param null|string|\SModders\TelegramCore\Entity\Bot $token
     * @param bool $onlyPrintable
     * @return bool|mixed
     * @throws Exception
     * @throws \XF\Mvc\Reply\Exception
     * @throws PrintableException
     */
    protected function assertSuccessRun(\Closure $call, $token = null, $onlyPrintable = false)
    {
        // Next code can throw exception.
        try {
            if ($token !== null && is_string($token))
            {
                $token = $this->telegram()->api($token);
            }
            else if ($token instanceof Bot)
            {
                $token = $token->Api;
            }

            return $call($token);
        }
        catch (Exception $e)
        {
            if (!$this->isConnectionExistsByException($e, $error))
            {
                throw $this->exception($this->message(\XF::phrase('smodders_tgcore.failure_connection', ['message' => $error])));
            }

            throw $onlyPrintable ? new PrintableException($e->getMessage(), 500) : $e;
        }
    }

    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected function telegram()
    {
        return $this->app->get('smodders.telegram');
    }
}