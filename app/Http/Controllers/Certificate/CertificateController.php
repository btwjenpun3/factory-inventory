<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CertificateController extends Controller
{    
    public function index() 
    {
        $users = User::get();
        return view('pages.system.certificate.index', [
            'users' => $users
        ]);
    }

    public function generateCertificate(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        // Buat pasangan kunci pribadi dan sertifikat umum untuk pengguna
        $keyPair = $this->generateKeyPair();

        // Simpan kunci pribadi di storage (sesuaikan dengan kebijakan keamanan)
        $privateKeyPath = $this->saveKeyToFile($keyPair['privateKey'], 'user_' . $request->id . '_' . $user->name . '_private_key.pem');

        // Simpan sertifikat umum di storage
        $publicCertificatePath = $this->saveKeyToFile($keyPair['publicCertificate'], 'user_' . $request->id . '_' . $user->name . '_public_certificate.crt');

        // Simpan path kunci pribadi dan sertifikat umum ke database pengguna        
        $user->update([
            'private_key_path' => $privateKeyPath,
            'public_certificate_path' => $publicCertificatePath,
        ]);

        return response()->json([
            'success' => 'Certificate created successfully'
        ]);
    }

    public function getPassphrase()
    {
        $passphrase = env('PASSPHRASE');
        return $passphrase;
    }

    protected function generateKeyPair()
    {        
        $dn = [
            "countryName"               => "ID",
            "stateOrProvinceName"       => "Indonesia",
            "localityName"              => "Ungaran",
            "organizationName"          => "IT Inventory",
            "organizationalUnitName"    => "It Inventory Team",
            "commonName"                => "Muhamad Helmi",
            "emailAddress"              => "muhamadkelmi@gmail.com"
        ];
        
        // Generate a new private (and public) key pair
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        
        // Generate a certificate signing request
        $csr = openssl_csr_new($dn, $privateKey, ['digest_alg' => 'sha256']);
        
        // Generate a self-signed cert, valid for 365 days
        $x509 = openssl_csr_sign($csr, null, $privateKey, 365, ['digest_alg' => 'sha256']);        
        
        openssl_x509_export($x509, $certout);
        openssl_pkey_export($privateKey, $pkeyout, $this->getPassphrase());

        if (openssl_csr_export($csr, $csrout) && openssl_x509_export($x509, $certout) && openssl_pkey_export($privateKey, $pkeyout, $this->getPassphrase())) {
            return [
                'privateKey'        => $pkeyout,
                'publicCertificate' => $certout
            ];
        } else {
            return ['error' => 'Failed to export keys or sign certificate'];
        }
    }   

    protected function saveKeyToFile($keyContent, $fileName)
    {
        $filePath = 'certificates/' . $fileName;
        Storage::put($filePath, $keyContent);

        return $filePath;
    }

    public function downloadCertificate(Request $request)
    {
        // Cari pengguna berdasarkan ID
        $user = User::find($request->id);

        // Pastikan pengguna ditemukan dan memiliki path sertifikat
        if ($user && $user->public_certificate_path) {
            // Dapatkan path lengkap ke file sertifikat
            $certificatePath = storage_path('app/' . $user->public_certificate_path);

            // Periksa apakah file sertifikat ada
            if (file_exists($certificatePath)) {
                // Mendapatkan nama file untuk memberikan nama file unduhan
                $filename = basename($certificatePath);
                // Menggunakan headers untuk mengatur jenis konten dan nama file
                return response()->download($certificatePath, $filename, [
                    'Content-Type' => 'application/x-pem-file',
                ]);
            }
        }

        // Jika pengguna tidak ditemukan atau path sertifikat tidak valid, redirect atau memberikan respon sesuai kebutuhan
        return response()->json(['error', 'Certificate not found'], 422);
    }
}
