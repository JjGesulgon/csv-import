<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ContactRepository;
use App\Http\Resources\ContactResource;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\ContactsImport;


class ContactsController extends Controller
{
    /**
     * contact repository.
     *
     * @var App\Repositories\ContactRepository
     */
    protected $contact;

    /**
     * Create new instance of about controller.
     *
     * @param ContactRepository passion Passion repository
     */
    public function __construct(ContactRepository $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Display the first resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $contact = ContactResource::collection(
        $this->contact->paginateWithFilters(request(), request()->per_page, request()->order_by)
      );
  
      if (! $contact) {
          return response()->json([
              'message' => 'Failed to retrieve resource'
          ], 400);
      }
  
      return $contact;
    }

    /**
       * Import the CSV File.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file'
        ]);

        $path = $request->file('import_file');
        $data = Excel::import(new ContactsImport, $path);
        
        //return $collection = (new ContactsImport)->toCollection($path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function readData(Request $request){
      $request->validate([
        'import_file' => 'required|file|mimes:csv,txt'
      ]);

      //get all headers of the csv file
      $headers = (new HeadingRowImport)->toArray($request->file('import_file'));

      /*
      * ****************************************************
      * Todo: Check if header is a custom header or not
      * ****************************************************
      */
            // foreach Contact::fillables as field
            //   foreach excelheader as header
            //       if field != header
            //           this is custom header
            //       else
            //           this is a field from contact
      /*
      * ****************************************************
      *
      * ****************************************************
      */      

      $path = $request->file('import_file');
      $delimiter = ',';

      if (!file_exists($path) || !is_readable($path))
      return false;

      $header = null;
      $data = array();
      if (($handle = fopen($path, 'r')) !== false)
      {
          while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
          {
              if (!$header)
                  $header = $row;
              else
                  $data[] = array_combine($header, $row);
          }
          fclose($handle);
      }

      // return $data;
      return response()->json([
        'numberOfRows' => sizeOf($data),
        'dataFromFile' => $data,
        'headers' => $headers[0][0]
    ], 200);
    }

    /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
    public function show($id)
    {
        if (! $passion = $this->passion->findOrFail($id)) {
            return response()->json([
                'message' => 'Resource does not exist'
            ], 400);
        }

        return response()->json([
            'message' => 'Resource successfully retrieve',
            'passion' => $passion
        ], 200);
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
        $validator = Validator::make($request->all(), [
          'name'          => 'required',
          'description'   => 'required',
          'image'         => 'max:2000',
        ]);

            if ($validator->fails()) {
                return response()->json([
            'message' => 'Validation failed',
            'errors'  => $validator->errors()
          ], 400);
            }

            if (! $this->passion->update($request, $id)) {
                return response()->json([
            'message' => 'Failed to update resource'
          ], 500);
            }

            return response()->json([
          'message' => 'Resource successfully updated'
        ], 200);
    }
}
