<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Person;
use Illuminate\Http\Request;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function create($id)
    {
        $books = Book::where('person_id','=',$id)->orderBy('year', 'DESC')->get()->toArray();
        $person = Person::where('id', $id )->get()->toArray();

        return view('applicant.book.create',compact('id','books','person'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'publisher' => 'required|min:3',
            'year' => 'required',
        ]);
        try {
                $person = loggedApplicant();

                $book = new Book;
                $book->person_id = $request->book_add_hidden_id;
                $book->title = $request->title;
                $book->publisher = $request->publisher;
                $book->year = $request->year;
                $book->save();
                return redirect()->back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show($id)
    {
        //
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
        $person = loggedApplicant();
        try {
            $book = Book::find($id);
            if(!empty($book)) $book->delete();
            return redirect()->back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
