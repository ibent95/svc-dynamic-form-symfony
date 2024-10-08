<?php

namespace App\Controller\V1\Master;

use App\Entity\DynamicFormFieldOptions;
use App\Service\CommonService;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DynamicFormFieldOptionsQueryController extends AbstractController
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

    /** ================================ Required functions for dynamicFormField ================================ */

    #[Route('/api/v1/dynamic-form/field-options', methods: ['GET'], name: 'app_v1_dynamic_form_field_options')]
    public function index(): JsonResponse
    {
        $this->logger->info('The dynamic form field options menu has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Success to access the dynamic form field options API!';
        $this->responseData['data'] = [
            'message' => 'Welcome to dynamic form field options!',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/dynamic-form/field-options', methods: ['GET'], name: 'app_v1_dynamic_form_field_options')]
    public function getAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // DynamicFormFieldOptions
            $dynamicFormFieldOptionsParams          = [];
            $dynamicFormFieldOptions                = $entityManager->getRepository(DynamicFormFieldOptions::class)->findBy($dynamicFormFieldOptionsParams);

            // Response data
            $this->responseData['data']     = $dynamicFormFieldOptions;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get dynamic form field options data!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get dynamic form field options data: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get dynamic form field options data!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Get dynamic form field options data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/master/dynamic-form/field-options', methods: ['GET'], name: 'app_v1_master_dynamic_form_field_options')]
    public function getMasterDataAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // DynamicFormFieldOptions
            $dynamicFormFieldOptionsParams          = [
                'flag_active' => true
            ];
            $dynamicFormFieldOptions                = $entityManager->getRepository(DynamicFormFieldOptions::class)->findBy($dynamicFormFieldOptionsParams);

            // Response data
            $this->responseData['data']     = $dynamicFormFieldOptions;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get master data of dynamic form field options!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get master data of dynamic form field options: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get master data of dynamic form field options!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Get master data of dynamic form field options exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}
