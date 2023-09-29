<?php

namespace App\Controller\V1;

use App\Entity\TemporaryFileUpload;
use App\Service\CommonService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainQueryController extends AbstractController
{
    private $logger;
    private $loggerMessage;
    private $responseData;
    private $responseStatusCode;
    private $commonSvc;

    public function __construct(
        LoggerInterface $logger,
        CommonService $commonSvc
    )
    {
        $this->logger = $logger;
        $this->loggerMessage        = 'No process is running.';
        $this->responseData = [
            'info'      => '',
            'message'   => '',
            'data'      => [],
        ];
        $this->responseStatusCode = 400;

        $this->commonSvc            = $commonSvc;
    }

    #[Route('/api/v1', name: 'app_v1_main')]
    public function index(): JsonResponse
    {
        $this->logger->info('The root route has been accessed!');

        $this->responseData['info']     = 'success';
        $this->responseData['message']  = 'Welcome to Dynamic Form service with Symfony 6.';
        $this->responseData['data']     = [
            'message'   => 'Welcome to Dynamic Form service with Symfony 6.',
            'date'      => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/files/{serviceName}/{fileNameExtension}', methods: ['GET'], name: 'app_v1_get_uploaded_file')]
    public function getUploadedFile(
        string $serviceName,
        string $fileNameExtension
    ): BinaryFileResponse
    {
        $filePath = $this->getParameter('secret_' . $serviceName . '_directory') . '/' . $fileNameExtension;
        $this->logger->info('Get uploaded file!');
        return new BinaryFileResponse($filePath);
    }

    #[Route('/api/v1/files/upload/{serviceName}', methods: ['POST'], name: 'app_v1_upload_file')]
    public function uploadFile(
        ManagerRegistry $doctrine,
        Request $request,
        string $serviceName
    ): JsonResponse
    {
        /** @var $entityManager EntityManager */
        $entityManager = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 200;
        $this->loggerMessage            = 'Save Publication data is running.';

        try {
            $entityManager->getConnection()->beginTransaction();

            $files              = $request->files->get('files') ?? null;
            $uploadedFiles      = [];
            $uploadedFileNames  = [];

            $tempFileUpload     = new TemporaryFileUpload();
            $tempFileUpload->setId($this->commonSvc->createUUIDShort());
            $tempFileUpload->setUuid($this->commonSvc->createUUID());

            if (count($files)) {
                foreach ($files as $fileIndex => $file) {
                    $uploadedFiles[] = ($file)
                        ? $this->commonSvc->uploadFile(
                            $file,
                            $serviceName . '_directory',
                            'api/v1/files/' . $serviceName
                        )
                        : null;

                    $uploadedFileNames[] = ($file)
                        ? $uploadedFiles[$fileIndex]['original_name']
                        : null;
                }

                if (count($uploadedFiles)) {
                    $tempFileUpload->setUploadedDatetime(new DateTime());
                    $tempFileUpload->setValue(implode(',',$uploadedFileNames));
                    $tempFileUpload->setOtherValue($uploadedFiles);

                    $this->responseData['data'] = $tempFileUpload->getUuid();

                    $entityManager->persist($tempFileUpload);
                }
            }

            $entityManager->flush();
            $entityManager->getConnection()->commit();

            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success on upload file data!';
            $this->logger->info($this->loggerMessage, $uploadedFileNames);
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack();

            $this->responseData['message']  = 'Error on upload file data!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Upload file data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), $e->getTraceAsString()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}
