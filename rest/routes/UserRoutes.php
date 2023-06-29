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


Flight::route('DELETE /api/users/@id', function ($id) {
    Flight::userService()->delete($id);
    Flight::json(['message' => "User deleted successfully"]) ;
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
        unset($user['password']);
        $user['is_admin'] = false;
        $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
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
