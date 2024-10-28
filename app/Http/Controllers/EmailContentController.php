<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\EmailContentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class EmailContentController extends Controller
{
    protected $emailContentRepository;

    public function __construct(EmailContentRepositoryInterface $emailContentRepository)
    {
        $this->emailContentRepository = $emailContentRepository;
    }

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $datas = $this->emailContentRepository->getById($userId);
        return view('pages.email-content.index', compact('datas'));
    }

    public function update(Request $request, $id)
    {
        $newDetails = Arr::except($request->all(),['_token', '_method']);
        $this->emailContentRepository->update($id, $newDetails);

        return redirect()->back()->with('success', "Auto reply email anda berhasil di update akan aktif pada tanggal " . $request->start_date . " hingga " .  $request->end_date);
    }
}
