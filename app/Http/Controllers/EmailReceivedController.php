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
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);

        $userId = Auth::user()->id;
        $emailRecieved = $this->emailRecievedRepository->getAllById($userId, $search, $perPage);

        // Check if the operation was successful
        $datas = $emailRecieved['datas'];

        // Handle redirection for empty results on a non-first page
        if ($datas->isEmpty() && $page > 1) {
            return redirect()->route('email-received', [
                'search' => $search,
                'per_page' => $perPage,
                'page' => 1
            ]);
        }

        // Determine success or error response
        $messageType = $emailRecieved['sukses'] ? 'success' : 'error';
        $pesan = $emailRecieved['pesan'];
        if ($messageType === "success") {
            $newEmail = $emailRecieved['jumlah_berhasil'];
            return view('pages.email-received.index', compact('datas', 'search', 'perPage', 'page', 'messageType', 'pesan', 'newEmail'));
        } else {
            return view('pages.email-received.index', compact('datas', 'search', 'perPage', 'page', 'messageType', 'pesan'));
        }

    }

}
