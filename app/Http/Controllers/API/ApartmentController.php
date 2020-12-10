<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Apartment;
use App\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\carbon;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $validator = Validator::make($request->all(),[
             'lat'=>'required',
             'lng'=>'required',
             'maxDist'=>'required|numeric|min:20|max:100',
             'sponsored' =>'boolean',
        ]);

        if($validator->fails()){
            return response()->json($validator->messages());
        }



        //controllo di $request->sponsored per la selezione della query
        if($request->sponsored) {
            
            //se sono richiesti gli sponsorizzati
            $query = Apartment::selectRaw("*, ST_Distance_Sphere(point($request->lng,$request->lat),
            point(longitude, latitude)) * .001 as distance")
            ->having('distance','<=',$request->maxDist)
            ->where('attivo', '=', '1')
            ->whereHas('sponsors',function($q){
                $q->where('end_sponsor','>=', Carbon::now());
            })->inRandomOrder();
            
        } else {
            //se sono richiesti tutti gli appartamenti
            $query = Apartment::selectRaw("*, ST_Distance_Sphere(point($request->lng,$request->lat),
            point(longitude, latitude)) * .001 as distance")
            ->having('distance','<=',$request->maxDist)
            ->where('attivo', '=', '1');
            /// da implementare per ulteriori servizi volendo
            if($request->has(['rooms','beds','bathrooms'])){
                $query->whereRaw("rooms >= $request->rooms AND beds >= $request->beds AND bathrooms >= $request->bathrooms");
            }
        }
        
        // FILTRO per servizi se presenti
        if($request['services']) {
            $serv = $request['services'];
            foreach($serv as $ser) {
                $query->whereHas('services', function (Builder $b) use ($ser) {
                $b->where('services.id', '=', $ser );

                });
            }
        }

        $query = $query->orderBy('distance','asc')->get();


        return response()->json($query, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
               'title' => 'required|min:10|max:300',
               'rooms'=>'required|numeric|min:1',
               'beds' =>'required|numeric|min:1',
               'bathrooms'=>'required|min:1',
               'sm'=>'required|min:1',
               'address'=>'required|min:3',
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
        ]
    );
        if($validator->fails()){
            $error = $validator->messages();
            return response()->json($error);
        }
        $apartment = Apartment::create($request->all());
        /// iserisce i servizi nella tabella pivot
        $apartment->services()->attach($request['services']);

        // if (!empty($request['img'])) {
        //     $request['img'] = Storage::disk('public')->put('images', $request['img']);
        // }

        return response()->json($apartment,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
       $app = Apartment::where('id','=',$id)->get();
        return response()->json($app);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment){
        $apartment->update($request->all());
        $apartment->services()->sync($request['services']);
        return response()->json($apartment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
         $apartment->delete();
         $message = [
           'messagio' =>'appartamento numero '.$apartment->id.' cancellato con successo'
         ];
         return response()->json($message);
    }

    public function getServices($id){

    }



}
