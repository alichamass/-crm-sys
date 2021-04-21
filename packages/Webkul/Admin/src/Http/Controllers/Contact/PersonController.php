<?php

namespace Webkul\Admin\Http\Controllers\Contact;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Attribute\Http\Requests\AttributeForm;
use Webkul\Contact\Repositories\PersonRepository;

class PersonController extends Controller
{
    /**
     * PersonRepository object
     *
     * @var \Webkul\Product\Repositories\PersonRepository
     */
    protected $personRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Product\Repositories\PersonRepository  $personRepository
     *
     * @return void
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;

        request()->request->add(['entity_type' => 'persons']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin::contacts.persons.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Webkul\Attribute\Http\Requests\AttributeForm $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('contacts.person.create.before');

        $person = $this->personRepository->create(request()->all());

        Event::dispatch('contacts.person.create.after', 1);
        
        session()->flash('success', trans('admin::app.contacts.persons.create-success'));

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $person = $this->personRepository->findOrFail($id);

        return view('admin::contacts.persons.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Webkul\Attribute\Http\Requests\AttributeForm $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, $id)
    {
        Event::dispatch('contacts.person.update.before');

        $person = $this->personRepository->update(request()->all(), $id);

        Event::dispatch('contacts.person.update.after', $person);
        
        session()->flash('success', trans('admin::app.contacts.persons.update-success'));

        return redirect()->route('admin.contacts.persons.index');
    }
}