<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CertificateLoginController extends Controller
{
    private $fileName;

    public function index()
    {
        return view('auth.certificate.index');
    }

    public function purgeCertificateSession() 
    {
        try {
            session()->forget('authenticated_with_certificate');
            return redirect()->route('verify.index');
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   

    public function authenticateWithCertificate(Request $request)
    {
        try {
            $validate = $request->validate([
                'certificate' => 'required|file|mimes:txt|mimetypes:text/plain',
            ]);
            if($validate){
                $uploadedCertificate    = $request->file('certificate');
                $this->fileName         = $uploadedCertificate->getClientOriginalName();
                $path = $uploadedCertificate->storeAs('uploaded_certificates', $this->fileName);             
                if ($this->validateCertificate($path)) {   
                    Storage::delete($path);           
                    session(['authenticated_with_certificate' => true]);            
                    return redirect()->route('auth.login.index');
                }
                Storage::delete($path);
                return redirect()->back()->with(['error' => 'Invalid Certificate!']);
            } else {                
                return redirect()->back()->with(['error' => 'Invalid Certificate!']);
            }            
        } catch(\Exception $e) {
            return redirect()->back()->with(['error' => 'Forbidden. Please use valid file!']);
        }
        
    }

    private function validateCertificate($certificatePath)
    {
        try {  
            $getName        = str_replace('public_certificate.crt', 'private_key.pem', $this->fileName);
            $privateKeyPath = storage_path('app/certificates/' . $getName);
            if(file_exists($privateKeyPath)) {
                $certificatePath    = storage_path('app/' . $certificatePath); 
                $certificateContent = file_get_contents($certificatePath);
                $privateKeyContent  = file_get_contents($privateKeyPath);
                $getPasshprase      = new CertificateController;
                $passphrase         = $getPasshprase->getPassphrase();            
                $privateKey =  openssl_pkey_get_private($privateKeyContent, $passphrase);   
                return openssl_x509_check_private_key($certificateContent, $privateKey);
            } else {
                return false;
            }   
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }       
    }
}
