<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\MailServerRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class MailServerController extends Controller
{
    protected $mailServerRepository;

    public function __construct(MailServerRepositoryInterface $mailServerRepository)
    {
        $this->mailServerRepository = $mailServerRepository;
    }

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $datas = $this->mailServerRepository->getById($userId);
        return view('pages.mail-server.index', compact('datas'));
    }

    public function update(Request $request, $id)
    {
        $newDetails = Arr::except($request->all(),['_token', '_method']);
        $this->mailServerRepository->update($id, $newDetails);

        return redirect()->back()->with('success', 'Mail Server berhasil diubah');
    }
}
