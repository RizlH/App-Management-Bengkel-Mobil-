<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard')->with('swal', [
                'icon' => 'info',
                'title' => 'Info',
                'text' => 'Anda sudah login!'
            ]);
        }
        
        return view('auth/login');
    }

    public function attemptLogin()
    {
        // AJAX request handling
        if ($this->request->isAJAX()) {
            return $this->ajaxLogin();
        }
        
        // Regular POST request
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
        
        $messages = [
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid'
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Cek apakah email terdaftar
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Login Gagal',
                    'text' => 'Email tidak terdaftar!'
                ]);
        }

        // Cek password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Login Gagal',
                    'text' => 'Password salah!'
                ]);
        }

        // Login berhasil
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'logged_in' => true
        ]);

        return redirect()->to('/dashboard')->with('swal', [
            'icon' => 'success',
            'title' => 'Login Berhasil!',
            'text' => 'Selamat datang ' . $user['full_name']
        ]);
    }

    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'full_name' => 'required|min_length[3]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'security_answer' => 'required|min_length[2]'
        ];
        
        $messages = [
            'username' => [
                'required' => 'Username wajib diisi',
                'min_length' => 'Username minimal 3 karakter',
                'max_length' => 'Username maksimal 50 karakter',
                'is_unique' => 'Username sudah digunakan'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email sudah terdaftar'
            ],
            'full_name' => [
                'required' => 'Nama lengkap wajib diisi',
                'min_length' => 'Nama lengkap minimal 3 karakter'
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi',
                'matches' => 'Password tidak cocok'
            ],
            'security_answer' => [
                'required' => 'Jawaban keamanan wajib diisi',
                'min_length' => 'Jawaban keamanan minimal 2 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $model = new UserModel();
        
        // Hash security answer untuk keamanan
        $securityAnswer = strtolower(trim($this->request->getPost('security_answer')));
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'security_answer' => password_hash($securityAnswer, PASSWORD_DEFAULT)
        ];

        if ($model->insert($data)) {
            return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }

    public function forgotPassword()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/forgot_password');
    }

    public function processForgotPassword()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email',
            'security_answer' => 'required|min_length[2]'
        ];
        
        $messages = [
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid'
            ],
            'security_answer' => [
                'required' => 'Jawaban keamanan wajib diisi',
                'min_length' => 'Jawaban keamanan minimal 2 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $model = new UserModel();
        $email = $this->request->getPost('email');
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email tidak ditemukan');
        }

        // Verify security answer
        $securityAnswer = strtolower(trim($this->request->getPost('security_answer')));
        
        if (!password_verify($securityAnswer, $user['security_answer'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jawaban keamanan salah!');
        }

        // Generate token untuk reset password
        $token = bin2hex(random_bytes(32));
        $expireTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        $model->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expire' => $expireTime
        ]);

        // Simpan user_id di session untuk validasi
        session()->set('reset_user_id', $user['id']);

        return redirect()->to('/auth/reset-password/' . $token)->with('success', 'Verifikasi berhasil! Silakan buat password baru.');
    }

    public function resetPassword($token = null)
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        $model = new UserModel();
        $user = $model->where('reset_token', $token)
                      ->where('reset_token_expire >', date('Y-m-d H:i:s'))
                      ->first();

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Link reset password tidak valid atau sudah kadaluarsa.');
        }

        // Verifikasi user_id dengan session
        if (session()->get('reset_user_id') !== $user['id']) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Silakan verifikasi ulang.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];
        
        $messages = [
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi',
                'matches' => 'Password tidak cocok'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->with('errors', $validation->getErrors());
        }

        $model = new UserModel();
        $token = $this->request->getPost('token');
        $user = $model->where('reset_token', $token)
                      ->where('reset_token_expire >', date('Y-m-d H:i:s'))
                      ->first();

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid atau sudah kadaluarsa');
        }

        // Verifikasi user_id dengan session
        if (session()->get('reset_user_id') !== $user['id']) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Verifikasi gagal');
        }

        // Update password
        $model->update($user['id'], [
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expire' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Hapus session reset
        session()->remove('reset_user_id');

        return redirect()->to('/auth/login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah berhasil logout.');
    }

    private function ajaxLogin()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors(),
                'title' => 'Validasi Error'
            ]);
        }

        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $model->where('email', $email)->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email tidak terdaftar!',
                'title' => 'Login Gagal'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password salah!',
                'title' => 'Login Gagal'
            ]);
        }

        // Login berhasil
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'logged_in' => true
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Selamat datang ' . $user['full_name'],
            'redirect' => base_url('dashboard')
        ]);
    }
}