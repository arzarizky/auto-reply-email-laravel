<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\EmailRecievedRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class EmailReceivedController extends Controller
{
    protected $emailRecievedRepository;

    public function __construct(EmailRecievedRepositoryInterface $emailRecievedRepository)
    {
        $this->emailRecievedRepository = $emailRecievedRepository;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 5);
            $page = max(1, (int) $request->input('page', 1)); // Pastikan halaman minimal 1

            $userId = Auth::id();
            $emailRecieved = $this->emailRecievedRepository->getAllById($userId, $search, $perPage);

            // Ambil data dari hasil query
            $datas = Arr::get($emailRecieved, 'datas', collect());

            // Handle redirection jika halaman kosong tapi bukan halaman pertama
            if ($datas->isEmpty() && $page > 1) {
                return redirect()->route('email-received', [
                    'search' => $search,
                    'per_page' => $perPage,
                    'page' => 1
                ])->with('message', 'Halaman kosong, dialihkan ke halaman pertama.');
            }

            // Ambil data tambahan
            $messageType = Arr::get($emailRecieved, 'sukses', false) ? 'success' : 'error';
            $pesan = Arr::get($emailRecieved, 'pesan', '');
            $newEmail = Arr::get($emailRecieved, 'jumlah_berhasil', 0);

            // Data yang dikirim ke tampilan
            $dataView = compact('datas', 'search', 'perPage', 'page', 'messageType', 'pesan');

            // Tambahkan `newEmail` jika sukses
            if ($messageType === 'success') {
                $dataView['newEmail'] = $newEmail;
            }

            return view('pages.email-received.index', $dataView);
        } catch (\Exception $e) {
            return redirect()->route('email-received')->with($e->getMessage());
        }
    }
}
