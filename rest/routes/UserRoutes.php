<?php
/**
 * @OA\Get(path="/api/users", tags={"users"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all users from the API. ",
 *         @OA\Response( response=200, description="List of students.")
 * )
 */

Flight::route('GET /api/users', function () {
   $user = Flight::get('user') ;
    Flight::json(Flight::userService()->get_all());
});


Flight::route('GET /api/users/@id', function ($id) {
    Flight::json(Flight::userService()->get_by_id($id));
});


Flight::route('GET /api/users/@firstName/@lastName', function ($firstName, $lastName) {
    Flight::json(Flight::userService()->getUserByFirstNameAndLastName($firstName, $lastName));
    });


Flight::route('POST /api/users', function () {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->add($data));
});


Flight::route('PUT /api/users/@id', function ($id) {
    $data = Flight::request()->data->getData();
    Flight::userService()->update($id, $data);
    Flight::json(Flight::userService()->get_by_id($id));
    Flight::json(['message' => "User updated successfully"]) ;
});
 /**
 * @OA\Delete(
 *     path="users/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Delete user",
 *     tags={"students"},
 *     @OA\Parameter(in="path", name="id", example=1, description="User ID"),
 *     @OA\Response(
 *         response=200,
 *         description="Note deleted"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */

Flight::route('DELETE /api/users/@id', function ($id) {
    Flight::userService()->delete($id);
    Flight::json(['message' => "User deleted successfully"]) ;
});

Flight::route('PUT /api/update-balance', function() {
    $requestData = Flight::request()->data->getData();
    
    // Retrieve the user ID and balance from the request data
    $user_id = $requestData['userId'];
    $balance = $requestData['balance'];
    
    // Perform necessary validation and sanitization on the user ID and balance
    
    // Update the balance in the database using your existing connection code
    
    // Assuming you have a User service class with appropriate methods, update the balance
    Flight::userService()->updateBalance($user_id, $balance);
    
    // Send a response back to the client
    Flight::json(['success' => true]);
});




use Firebase\JWT\JWT;
use Firebase\JWT\Key;

 /**
* @OA\Post(
*     path="/login", 
*     description="Login",
*     tags={"login"},
*     @OA\RequestBody(description="Login", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*             @OA\Property(property="email", type="string", example="demo@gmail.com",	description="Student email" ),
*             @OA\Property(property="password", type="string", example="12345",	description="Password" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Logged in successfuly"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::userDao()->get_user_by_email($login['email']);
    if(count($user) > 0){
        $user = $user[0];
    }
    if (isset($user['idUsers'])){
      if($user['password'] == md5($login['password'])){
        $expiration_time =  time() + 3600 ;
        unset($user['password']);
        $userData = [
            'idUsers' => $user['idUsers'],
            'email' => $user['email'],
            'exp' => $expiration_time
        ];
      //  $user['is_admin'] = false;
        $jwt = JWT::encode($userData, Config::JWT_SECRET(), 'HS256');

        Flight::json(['token' => $jwt]);
      }else{
        Flight::json(["message" => "Wrong password"], 404);
      }
    }else{
      Flight::json(["message" => "User doesn't exist"], 404);
  }
});


Flight::route('POST /api/register', function(){
  $registration = Flight::request()->data->getData();
  $user = Flight::userDao()->get_user_by_email($registration['email']);
  
  if(count($user) > 0){
      Flight::json(["message" => "User already exists"], 409);
      return;
  }

  // Create a new user record
  $newUser = [
      'first_name' => $registration['first_name'],
      'last_name' => $registration['last_name'],
      'email' => $registration['email'],
      'password' => md5($registration['password']),
     // 'is_admin' => false
  ];

  // Save the new user to the database
  Flight::userDao()->add($newUser);

  // Generate JWT token for the registered user
  $jwt = JWT::encode($newUser, Config::JWT_SECRET(), 'HS256');
  
  // Remove the password field from the user object
  unset($newUser['password']);

  // Return the JWT token to the client
  Flight::json(['token' => $jwt]);
});

?>
