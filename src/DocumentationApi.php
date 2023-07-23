<?php

namespace App;

//use OpenApi\Annotations as OA;
use OpenApi\Attributes as OA;

#[OA\Info(title: 'Manavola Api', version: '0.1')]
#[OA\Server(
    url: 'http://manavolabackend-env.eba-sweq2qp9.us-east-2.elasticbeanstalk.com',
    description: 'Manavola backend'
)]
class DocumentationApi
{
}
// ./vendor/bin/openapi --format yaml --output ./public/swagger-ui/swagger.yaml src

/*
use OpenApi\Attributes as OA;

#[OA\Get(path: '/users/{id}')]
#[OA\Parameter(name: 'id', in: 'path', description: 'User ID', required: true)]
#[OA\Response(response: '200', description: 'Success')]
public function getUser(string $id): Response
{
    // Implementation here
}

use OpenApi\Attributes as OA;

#[OA\Post(path: '/users')]
#[OA\RequestBody(description: 'User data', required: true, content: [
    'application/json' => #[OA\MediaType(mediaType: 'application/json', schema: #[OA\Schema(type: 'object', properties: [
        'name' => #[OA\Property(type: 'string')],
        'email' => #[OA\Property(type: 'string', format: 'email')],
        'password' => #[OA\Property(type: 'string', format: 'password')],
    ])])
])]
#[OA\Response(response: '201', description: 'Created')]
public function createUser(Request $request): Response
{
    // Implementation here
}


#[OA\RequestBody(
        description: 'User data',
        required: true,
        content: [
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['email'],
                    properties: [
                        'name' => new OA\Property(property: 'name', type: 'string'),
                        'email' => new OA\Property(property: 'email', type: 'string', format: 'email'),
                        'password' => new OA\Property(property: 'password', type: 'string', format: 'password'),
                    ]
                )
            )
        ]
    )]


    //
    #[OA\RequestBody(
        request: 'create new user',
        required: true,
        content: new OA\JsonContent(
            [
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    example: 'example@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    example: '********'
                ),
                new OA\Property(
                    property: 'username',
                    type: 'string',
                    example: 'Rakoto'
                ),
                new OA\Property(
                    property: 'parrainerId',
                    type: 'string',
                    example: 'MVX59865'
                ),

            ]
        )
    )]




    use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController
{
    
 @Route("/example", methods={"POST"})
 @OA\Post(
     path="/example",
     summary="Example POST endpoint",
     @OA\RequestBody(
         required=true,
              @OA\MediaType(
                  mediaType="multipart/form-data",
                  @OA\Schema(
                      type="object",
                      @OA\Property(
                         property="json_data",
                          type="string",
                          description="JSON data",
                      ),
                      @OA\Property(
                          property="file_data",
                          type="string",
                          format="binary",
                         description="File data",
                      ),
                  ),
              ),
          ),
          @OA\Response(
              response="200",
              description="Successful operation",
          ),
          @OA\Response(
              response="400",
              description="Invalid request",
          ),
      )
     
    public function example(Request $request)
    {
        $jsonData = json_decode($request->request->get('json_data'), true);
        $fileData = $request->files->get('file_data');

        if (!$jsonData || !$fileData instanceof UploadedFile) {
            return new JsonResponse(['message' => 'Invalid request'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Handle the JSON and file data

        return new JsonResponse(['message' => 'Success'], JsonResponse::HTTP_OK);
    }
}

openapi: 3.0.0
info:
  title: Example API
  version: 1.0.0
paths:
  /upload:
    post:
      summary: Upload an image file
      requestBody:
        required: true
        content:






        #[Route('/users/{id}', methods: ['GET'])]
public function getUser(int $id): array
{
    // ...
}

public function createUser(
    #[Parameter(name: 'name', description: 'The name of the user')]
    string $name,
    #[Parameter(name: 'email', description: 'The email address of the user')]
    string $email
): array {
    // ...
}

#[Route('/users', methods: ['POST'])]
public function createUser(
    #[RequestBody(description: 'The user data')]
    array $data
): array {
    // ...
}

//Get
use OpenApi\Annotations as OA;

#[OA\Get(
    path: "/users",
    summary: "Get a list of users",
    responses: [
        #[OA\Response(
            response: "200",
            description: "Successful operation",
            content: #[OA\MediaType(
                mediaType: "application/json",
                schema: #[OA\Schema(
                    type: "array",
                    items: #[OA\Items(ref: "#/components/schemas/User")]
                )]
            )]
        )]
    ]
)]
public function getUsers() {
    // ...
}

use OA\Get;

class UserController {
    #[Get('/users/{id}', tags: ['Users'])]
    public function getUser(int $id): array {
        // retrieve user information from database
        return ['name' => 'John Doe', 'email' => 'johndoe@example.com'];
    }
}

Schema

use OA\Schema;
use OA\Property;

class UserController {
    #[OA\Post('/users')]
    public function createUser(#[OA\RequestBody()] #[Schema(type: 'object', properties: [
        #[Property('name', type: 'string')],
        #[Property('email', type: 'string', format: 'email')],
    ])] array $data): array {
        // create user in database
        return ['success' => true];
    }
}

use OA\Schema;
use OA\Property;

class UserController {
    #[OA\Post('/users')]
    public function createUser(#[OA\RequestBody()] #[Schema(type: 'object', properties: [
        #[Property('name', type: 'string')],
        #[Property('email', type: 'string', format: 'email')],
    ], tags: ['Users'])]
*/