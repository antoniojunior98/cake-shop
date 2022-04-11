<?php

namespace App\Http\Controllers;

use App\Mail\MailUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::select('name', 'email', 'id');
        if($search){
            $users->where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%");
        }
        
        return response()->json($users->paginate(8), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password'
        ]);

        $validate = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:20',
        ]);

        $this->verifyEmail($validate, $data);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try{
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->save();
            $subjectMatter = "Bem Vindo!";
            $title = "{$user->name}";
            $message = "Seja bem vindo ao Cake Shop!";
            Mail::to($user->email)->send(new MailUser($subjectMatter, $title, $message));

            return response()->json(['success' =>  'successfully created user!'], 201);
        } catch (Exception $e) {
            Log::info("[User]store: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->only([
            'name',
            'email',
            'current_password',
            'password',
        ]);

        $validate = Validator::make($data, [
            'name' => 'required|string|max:150',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8|max:20'
        ]);
        
        $this->verifyEmail($validate, $data, $user);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }
        try{
            $user->name = $data['name'];
            $user->email = $data['email'];
            if(!empty($data['password'])){
                $user->password = password_hash($data['password'], PASSWORD_DEFAULT); 
            }
            $user->save();
            $subjectMatter = "Usuário Atualizado";
            $title = "Usuário Atualizado";
            $message = "Seu usuário foi atualizado!";
            Mail::to($user->email)->send(new MailUser($subjectMatter, $title, $message));

            return response()->json(['success' => 'successfully updated user!'], 201);
        } catch (Exception $e){
            Log::info("[User]update: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }
    }

    private function verifyEmail($validate, array $data, $user = null): void
    {
        if(isset($data['email'])){
            $validate->after(function ($validate) use ($data, $user) {
                if(isset($user)){
                    if ($user->email != $data['email']) {
                        $hasEmail = User::where("email", $data['email'])->count();
                        if ($hasEmail === 0) {
                            return;
                        }
                        $validate->errors()->add('email', __('validation.unique', [
                            'attribute' => 'email',
                        ]));
                        return;
                    }
                } else {
                    $hasEmail = User::where("email", $data['email'])->count();
                    if ($hasEmail === 0) {
                        return;
                    }
                    $validate->errors()->add('email', __('validation.unique', [
                        'attribute' => 'email',
                    ]));
                    return;
                }
            });
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{
            $user->delete();
            $subjectMatter = "Usuário Excluido";
            $title = "Usuário Excluido";
            $message = "Seu usuário foi excluido!";
            Mail::to($user->email)->send(new MailUser($subjectMatter, $title, $message));
            return response()->json(['success' => 'successfully deleted user!']);
        } catch(Exception $e){
            Log::info("[User]Destroy: {$e->getMessage()}");
            return response()->json(['error' => 'internal server error'], 500);
        }  
    }
}