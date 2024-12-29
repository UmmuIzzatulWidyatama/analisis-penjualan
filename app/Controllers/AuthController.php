<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        helper(['form']); // Memuat helper form
        return view('login');
    }

    public function authenticate()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session->set([
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/dashboard'); // Redirect ke dashboard
            } else {
                $session->setFlashdata('error', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
