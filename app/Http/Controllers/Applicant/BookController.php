<?php

namespace App\Http\Controllers\Base;

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
        $user_id = \Auth::guard(get_role_cookie())->user()->id;
        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        $books= [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            $books = Book::where('person_id', $p_id)->get()->toArray();
        }
        return view('base.book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

        $books = Book::where('person_id','=',$id)->orderBy('year', 'DESC')->get()->toArray();
        $person = Person::where('id', $id )->get()->toArray();

        return view('base.book.create',compact('id','books','person'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'publisher' => 'required|min:3',
            'year' => 'required',
        ]);
        try {
            $user_id = \Auth::guard(get_role_cookie())->user()->id;  /*Petq e ardyoq avelacnem Cookie-i stugum???*/
            /*$person_id = Person::where('user_id', $user_id )->get()->toArray();
            $p_id  = $person_id[0]['id'];*/

                $book = new Book;
                $book->person_id = $request->book_add_hidden_id;
                $book->title = $request->title;
                $book->publisher = $request->publisher;
                $book->year = $request->year;
                $book->save();
                return \Redirect::back()->with('success', getMessage("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        return view('base.book.edit', compact('book', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = \Auth::guard(get_role_cookie())->user()->id;
        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        $p_id  = $person_id[0]['id'];

        $validatedData = $request->validate([
            'title.*' => 'required|min:3',
            'publisher.*' => 'required|min:3',
            'year.*' => 'required',
        ]);
        try {
            $count = count($request->book_edit_hidden_id);
            for ($i = 0; $i < $count; $i++) {
                $ansef_supported = '0';
                $domestic = '0';
                $book = Book::find($request->book_edit_hidden_id[$i]);
                $book->title = $request->title[$i];
                $book->publisher = $request->publisher[$i];
                $book->year = $request->year[$i];  /* vercnel miayn tarin te tuyl tal amboxy date mutqagrel?*/;
                $book->save();
            }
            return \Redirect::back()->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $book = Book::find($id);
            $book->delete();
            return \Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }
}
