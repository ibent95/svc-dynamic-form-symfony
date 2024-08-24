<?php

namespace App\Controller\V1\Master;

use App\Entity\DynamicFormFieldType;
use App\Service\CommonService;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DynamicFormFieldTypeController extends AbstractController
{

    private $logger;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $responseData;
    private $responseStatusCode;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger               = $logger;

        $this->request              = Request::createFromGlobals();

        $this->exprBuilder          = Criteria::expr();
        $this->criteria             = new Criteria();

        // Response initial value
        $this->responseData         = [
            'info'      => '',
            'message'   => '',
            'data'      => [],
        ];
        $this->responseStatusCode   = 400;
    }

    /** ================================ Required functions for publication ================================ */

    #[Route('/api/v1/dynamic-form/field-types', methods: ['GET'], name: 'app_v1_dynamic_form_field_types')]
    public function index(): JsonResponse
    {
        $this->logger->info('The dynamic form field types menu has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Success to access the dynamic form field types API!';
        $this->responseData['data'] = [
            'message' => 'Welcome to dynamic form field options!',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/dynamic-form/field-types', methods: ['GET'], name: 'app_v1_dynamic_form_field_types')]
    public function getAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // DynamicFormFieldType
            $dynamicFormFieldOptionsParams          = [];
            $dynamicFormFieldOptions                = $entityManager->getRepository(DynamicFormFieldType::class)->
                findBy($dynamicFormFieldOptionsParams);

            // Response data
            $this->responseData['data']     = $dynamicFormFieldOptions;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get dynamic form field types data!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get dynamic form field types data: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get dynamic form field types data!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Get dynamic form field types data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/master/dynamic-form/field-types', methods: ['GET'], name: 'app_v1_master_dynamic_form_field_types')]
    public function getMasterDataAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // DynamicFormFieldType
            $dynamicFormFieldOptionsParams          = [
                'flag_active' => true
            ];
            $dynamicFormFieldOptions                = $entityManager->getRepository(DynamicFormFieldType::class)->
                findBy($dynamicFormFieldOptionsParams);

            // Response data
            $this->responseData['data']     = $dynamicFormFieldOptions;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get master data of dynamic form field types!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get master data of dynamic form field types: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get master data of dynamic form field types!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Get master data of dynamic form field types exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}
