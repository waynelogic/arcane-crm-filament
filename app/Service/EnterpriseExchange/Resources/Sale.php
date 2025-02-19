<?php namespace App\Service\EnterpriseExchange\Resources;


use Waynelogic\Emporium\Import\Resources\Order;
use Waynelogic\Emporium\Import\Resources\Status;

class Sale extends AbstractResource
{
    /**
     * Инициализация ресурса
     * @return string
     */
    public function init() : string
    {
        $answer = [
            'zip=' . 'no',
            'file_limit=' . '52428800',
        ];
        if($_GET["version"] <> '')
        {
            $answer[] = session()->getId();
            $answer[] = 'version=2.09';
        }

        return $this->answer($answer);
    }

    public function info() : string
    {
        $this->contentType = 'text/xml';
        $view = \View::make('albus.shopsync::info', [
            'arStatuses' => Status::all()
        ]);
        return $view->render();
    }
    public function query() : string
    {
        $this->contentType = 'text/xml';
        $view = \View::make('albus.shopsync::orders', [
            'arOrders' => Order::all()
        ]);
        return $view->render();
    }

    public function parse() : bool
    {

        return true;
    }
}
