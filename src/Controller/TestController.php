<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
class TestController extends AbstractController
{
private array $users = [
['id' => 1, 'email' => 'test1@gmail.com', 'name' => 'John1'],
['id' => 2, 'email' => 'test2@gmail.com', 'name' => 'John2'],
['id' => 3, 'email' => 'test3@gmail.com', 'name' => 'John3'],
['id' => 4, 'email' => 'test4@gmail.com', 'name' => 'John4'],
['id' => 5, 'email' => 'test5@gmail.com', 'name' => 'John5'],
['id' => 6, 'email' => 'test6@gmail.com', 'name' => 'John6'],
['id' => 7, 'email' => 'test7@gmail.com', 'name' => 'John7'],
];

#[Route('/users', name: 'app_collection_users', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
public function getCollection(): JsonResponse
{
// Якщо користувач не є адміністратором, Symfony автоматично викине 403
return $this->json(['data' => $this->users], Response::HTTP_OK);
}

#[Route('/users/{id}', name: 'app_item_users', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
public function getItem(int $id): JsonResponse
{
// Якщо користувач не є адміністратором, Symfony автоматично викине 403
return $this->json(['data' => $this->findUserById($id)], Response::HTTP_OK);
}

#[Route('/users', name: 'app_create_users', methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
public function createItem(Request $request): JsonResponse
{
// Якщо користувач не є адміністратором, Symfony автоматично викине 403
$requestData = json_decode($request->getContent(), true);

if (!isset($requestData['email'], $requestData['name'])) {
throw new UnprocessableEntityHttpException("Both name and email are required");
}

if (!filter_var($requestData['email'], FILTER_VALIDATE_EMAIL)) {
throw new UnprocessableEntityHttpException("Invalid email format");
}

$newUser = [
'id'    => count($this->users) + 1,
'name'  => $requestData['name'],
'email' => $requestData['email'],
];

$this->users[] = $newUser;

return $this->json(['data' => $newUser], Response::HTTP_CREATED);
}

#[Route('/users/{id}', name: 'app_update_users', methods: ['PATCH'])]
#[IsGranted('ROLE_ADMIN')]
public function updateItem(int $id, Request $request): JsonResponse
{
// Якщо користувач не є адміністратором, Symfony автоматично викине 403
$user = &$this->findUserById($id);
$requestData = json_decode($request->getContent(), true);

if (isset($requestData['name'])) {
$user['name'] = $requestData['name'];
}
if (isset($requestData['email']) && filter_var($requestData['email'], FILTER_VALIDATE_EMAIL)) {
$user['email'] = $requestData['email'];
}

return $this->json(['data' => $user], Response::HTTP_OK);
}

#[Route('/users/{id}', name: 'app_delete_users', methods: ['DELETE'])]
#[IsGranted('ROLE_ADMIN')]
public function deleteItem(int $id): JsonResponse
{
// Якщо користувач не є адміністратором, Symfony автоматично викине 403
foreach ($this->users as $key => $user) {
if ($user['id'] === $id) {
unset($this->users[$key]);
return $this->json([], Response::HTTP_NO_CONTENT);
}
}

throw new NotFoundHttpException("User not found");
}

private function &findUserById(int $id): array
{
foreach ($this->users as &$user) {
if ($user['id'] === $id) {
return $user;
}
}

throw new NotFoundHttpException("User with ID $id not found");
}
}
