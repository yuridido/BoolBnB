<?php

namespace App\Http\Controllers\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use App\Apartment;
use App\Service;
use App\Image;
use App\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Braintree;
use Illuminate\Support\Facades\DB;


class HostController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SE AMMINISTRATORE VENGONO RESTITUITI TUTTI GLI APPARTAMENTI
        if ((Auth::user()->role->role) == "admin") {

            $apartments = Apartment::get();
            // SE UTENTE VENGONO VISUALIZZATI GLI APPARTAMENTI DA LUI REGISTRATI
        } elseif ((Auth::user()->role->role) == "host") {
            $apartments = Apartment::where('apartments.user_id', '=', Auth::id())
                ->get();
            // ->orderBy('created_at','desc');

        }

        
         /// ti fa vedere se uno degli appartamneti e sponsorizzato
        $spons = DB::table('apartment_sponsor')
        ->where('end_sponsor', '>', Carbon::now())
        ->orderBy('end_sponsor', 'desc')
        ->get();

        return view('logged.apartments', compact('apartments', 'spons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('logged.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:10|max:300',
            'rooms'=>'required|numeric|min:1',
            'beds' =>'required|numeric|min:1',
            'bathrooms'=>'required|min:1',
            'sm'=>'required|min:1',
            'address'=>'required|min:10',
            'latitude'=>'required',
            'longitude'=>'required',
            'city'=>'required|min:1',
            'postal_code'=>'required',
            'country'=>'required',
            'daily_price'=>'required',
            'description'=>'required|min:20',
            'user_id'=>'numeric|exists:users,id',
        ],
        [
            'required'=>':attribute is a required field',
            'numeric'=>':attribute must be a number',
            'exists'=>'the room need to be associated to an existing user',
        ]);
        if($validator->fails()){
            $error = $validator->messages();
            return response()->json($error);
        }

    // creazione dell'appartamento
    $apartment = Apartment::create($request->all());

    // creazione dei servizi correlati nella tabella pivot
    if (!empty($apartment->services())) {
        $apartment->services()->attach($request['services']);
    }

    // storage delle immagini e inserimento nel DB
    if(!empty($request->file('img'))) {
        $images = $request->file('img');
        foreach ($images as $image) {
            $image = Storage::disk('public')->put('images', $image);
            Image::insert(
                [
                    'created_at' => Carbon::now(),
                    'path' => $image,
                    'apartment_id' => $apartment->id,
                ]
            );
        }
    }




        return redirect()->route('host.index')->with('status', 'Hai creato correttamente il tuo annuncio "' . $apartment->title . '"');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if((Auth::user()->role->role)== "admin"){
            $apartment = Apartment::find($id);
        } elseif ((Auth::user()->role->role)== "host") {
            $apartment = Apartment::find($id);
            if($apartment->user_id != Auth::id()) {
                return abort(404);
            }
        }

       return view('logged.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $services = Service::all();
        $apartment = Apartment::find($id);
        if ($apartment->user_id == Auth::id()) {
            return view('Logged.edit', compact('apartment', 'services'));

        } else {
            return abort(404);
        }
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
        $data = $request->all();
        $data['updated_at'] = Carbon::now('Europe/Rome');
        $apartment = Apartment::find($id);
        $apartment->update($data);

        // AGGIORNAMENTO SERVIZI
        $apartment->services()->sync($data['services']);

        // AGGIUNTA IMMAGINI
        if(!empty($request->file('img'))) {
            $images = $request->file('img');

            foreach ($images as $image) {
                $image = Storage::disk('public')->put('images', $image);
                Image::insert(
                    [
                        'created_at' => Carbon::now(),
                        'path' => $image,
                        'apartment_id' => $apartment->id,
                    ]
                );
            }

        }



        return redirect()->route('host.index')->with('status', 'Hai modificato il tuo appartamento');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sponsor($id)
    {
        //trovo l'appartamento tramite il suo ID
        $apartment = Apartment::find($id);
        //prendo tutti i tipi di sponsorizzazione
        $sponsors = Sponsor::all();

        return view('logged.sponsor', compact('apartment','sponsors'));
    }

    public function pay($id,$id_sponsor,$price)
    {
        //trovo l'appartamento tramite il suo ID
        $apartment = Apartment::find($id);
        //prendo tutti i tipi di sponsorizzazione
        $sponsors = Sponsor::all();
        //creo gateway per creare nuovo TOKEN
        $gateway = new Braintree\Gateway(config('braintree'));
        $token = $gateway->ClientToken()->generate();

        return view('logged.pay', compact('apartment','token','sponsors','price','id_sponsor'));
    }

    public function visibility ($id){
        $apt = Apartment::find($id);
        $visibility = !($apt->attivo);

        if($apt->user_id == Auth::id()){
            Apartment::where('id',$id)->update(['attivo'=>$visibility]);
            return redirect()->route('host.index');
        }
        return redirect()->back();
    }


    public function checkout(Request $request, $id)
    {
        //Prendo i valori dal payment form
        $data = $request->all();
        $gateway = new Braintree\Gateway(config('braintree'));
        //Informazioni appartamento
        $apartment = Apartment::find($id);
        $apartment_id = $apartment->id;
        //Informazioni Sponsorizzazione
        $sponsor_id = $data['sponsor_plan'];

        $sponsor = Sponsor::find($sponsor_id);
        $sponsor_price = $data['amount'];
        $sponsor_durate = $sponsor->sponsor_time;


        //registro la transazione
        $result = $gateway->transaction()->sale([
                'amount' => $sponsor_price,
                'paymentMethodNonce' => $request['payment_method_nonce'],
                'customer' => [
                    'email' => Auth::user()->email,
                ],
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);


        // Check sulla transazione
        if ($result->success || !is_null($result->transaction)) {
            $transaction = $result->transaction;

            // Prendo la data corrente
            $start = Carbon::now();

            // Data di scadenza con check su precedenti sponsorizzazioni attive
            $checkSponsor = Apartment::join('apartment_sponsor', 'apartments.id', '=', 'apartment_sponsor.apartment_id')
            ->where('apartment_sponsor.apartment_id', '=', $id)
                ->where('apartment_sponsor.end_sponsor', '>=', Carbon::now())
                ->orderBy('apartment_sponsor.end_sponsor', 'desc')
                ->limit(1)
                ->get();

            if (count($checkSponsor) > 0) {
                $end_sponsor = Carbon::parse($checkSponsor[0]->end_sponsor)->addHours($sponsor_durate);
            } else {
                $end_sponsor = Carbon::now()->addHours($sponsor_durate);
            }


            // Id Transazione
            $transId = $transaction->id;

            // Popolare La Pivot apartmentSponsor
            $apartment->sponsors()->attach(
                $apartment_id,
                [
                    'start_sponsor' => $start,
                    'end_sponsor' => $end_sponsor,
                    'transaction_id' => $transId,
                    'apartment_id' => $apartment_id,
                    'sponsor_id' => $sponsor_id
                ]
            );
        } else {
            abort('404');
        }

        return redirect()->route('host.index')->with('status','Appartamento "'.$apartment->title .'" sponsorizzato con successo!');
    }

}
