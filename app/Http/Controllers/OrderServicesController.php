<?php

namespace App\Http\Controllers;

use App\Helpers\StatsHelper;
use App\Models\OrderService;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class OrderServicesController extends CrudController
{
    protected string $model = OrderService::class;
    protected string $resource = 'order_services';
    protected array $messages = [];

    /**
     * Export Services Statistics
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportServicesData()
    {
        $generator = (new StatsHelper())->servicesData();

        return response()->streamDownload(
            function() use ($generator) {
                $generator->save('php://output');
            },
            'services-data.xlsx'
        );
    }

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            "order_id"   => "sometimes|required|numeric",
            "service_id" => "sometimes|required|numeric",
            "quantity"   => "sometimes|required|numeric",
            "unit_price" => "sometimes|required|numeric",
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            "order_id"   => "required|numeric",
            "service_id" => "required|numeric",
            "quantity"   => "required|numeric",
            "unit_price" => "required|numeric",
        ];
    }

    /**
     * @param Model $service
     *
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Model $service)
    {
        if (!$service instanceof OrderService) {
            throw new InvalidArgumentException();
        }
        $this->validate(request(), $this->updateValidationRules(), $this->messages);

        $service->update(request()->all());

        return [
            "type" => "Success",
            "message" => "$this->name Successfully updated.",
            'item' => $service->load('service'),
        ];
    }

    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {

        $this->validate(request(), $this->storeValidationRules(), $this->messages);

        $data = request()->all();
        
        if (isset($data['service_id'])) {
            $serviceModel = \App\Models\Service::find($data['service_id']);
            if ($serviceModel) {
                $data['name'] = $serviceModel->name;
                if (!isset($data['unit_price'])) {
                    $data['unit_price'] = $serviceModel->price;
                }
            }
        }

        $service = OrderService::create($data);
        $item = OrderService::find($service->id)->load('service');

        return [
            "type" => "success",
            "message" => "$this->name Successfully Created",
            "item" => $item,
        ];
    }
}
