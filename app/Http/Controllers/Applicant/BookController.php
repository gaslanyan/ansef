<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Person;
use Illuminate\Http\Request;


class BookController extends Controller
{
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $books = Book::where('person_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->orderBy('year', 'DESC')->get()->toArray();
        $person = Person::where('id', $id)
            ->where('user_id', '=', $user_id)
            ->get()->toArray();

        return view('applicant.book.create', compact('id', 'books', 'person'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'publisher' => 'required|min:3',
            'year' => 'required',
        ]);
        try {
            $book = new Book;
            $book->person_id = $request->book_add_hidden_id;
            $book->title = $request->title;
            $book->publisher = $request->publisher;
            $book->year = $request->year;
            $book->user_id = $user_id;
            $book->save();
            return redirect()->back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();

        $validatedData = $request->validate([
            'title.*' => 'required|min:3',
            'publisher.*' => 'required|min:3',
            'year.*' => 'required',
        ]);
        try {
            $count = count($request->book_edit_hidden_id);
            for ($i = 0; $i < $count; $i++) {
                $book = Book::find($request->book_edit_hidden_id[$i]);
                if ($book->user_id != $user_id) continue;
                $book->title = $request->title[$i];
                $book->publisher = $request->publisher[$i];
                $book->year = $request->year[$i];
                $book->save();
            }
            return redirect()->back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $book = Book::where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
            if (!empty($book)) $book->delete();
            return redirect()->back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
