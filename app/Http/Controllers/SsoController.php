<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\URL;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use UserHelp;

class SsoController extends Controller
{
    //

    private $_provider;

    private $_config;

    public function __construct()
    {
        // $this->_config = [
        //               'clientId'                => 'acaab266-9f8f-43d8-b08e-c490676edefe',
        //               'clientSecret'            => 'H1/OIDdnnujWy0lS9l2XTjQ26m1R3Q4zljZMQv9b3Sk=',
        //               'redirectUri'             => 'http://localhost:8000/sso/authen',
        //               'urlAuthorize'            => 'https://login.microsoftonline.com/03290435-ff74-45d1-aeaa-173677221cf8/oauth2/v2.0/authorize',
        //               'urlAccessToken'          => 'https://login.microsoftonline.com/03290435-ff74-45d1-aeaa-173677221cf8/oauth2/v2.0/token',
        //               'urlResourceOwnerDetails' => '',
        //               'scopes'                  => 'profile openid offline_access email User.Read',
        //           ] ;

        $this->_config = [
            'clientId'                => 'd4e33023-d86d-4234-8a41-cd60a2145e36',
            'clientSecret'            => 'HHIQsID9tD9Tyi+s9TEnpm1w8yfRnBuT78N3UQodUEA=',
            'redirectUri'             => url('/') . '/sso/authen',
            'urlAuthorize'            => 'https://login.microsoftonline.com/03290435-ff74-45d1-aeaa-173677221cf8/oauth2/v2.0/authorize',
            'urlAccessToken'          => 'https://login.microsoftonline.com/03290435-ff74-45d1-aeaa-173677221cf8/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes'                  => 'profile openid offline_access email User.Read',
        ];
    }

    public function login(Request $request)
    {

        if ($request->session()->has('is_login')) {
            session_destroy();
            return redirect('/dashboard');
        }



        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider($this->_config);

        $authUrl = $oauthClient->getAuthorizationUrl();

        // Save client state so we can validate in callback
        // session(['oauthState' => $oauthClient->getState()]);
        // $this->session->set_userdata('oauthState', $oauthClient->getState());

        $request->session()->put('oauthState', $oauthClient->getState());

        // dd($authUrl);

        // Redirect to AAD signin page
        // header('Location: '.$authUrl);
        // redirect($authUrl);
        //
        return redirect()->to($authUrl);
    }

    public function authen(Request $request)
    {
        // Validate state
        $expectedState = $request->session()->get('oauthState');
        $request->session()->forget('oauthState');
        // $request->session()->forget('oauthState');
        // $providedState = $request->query('state');
        $providedState = $request->get('state');

        // echo $expectedState ; echo '<br>' ;
        // echo $providedState ; echo '<br>' ;
        // echo '<pre>' ;
        // die;


        if (!isset($expectedState) || !isset($providedState) || $expectedState != $providedState) {
            // return redirect('/')
            // ->with('error', 'Invalid auth state')
            // ->with('errorDetail', 'The provided auth state did not match the expected value');
            // die('die');
            abort(403, 'Unauthorized action.');
        }

        // Authorization code should be in the "code" query param
        // $authCode = $request->query('code');
        $authCode = $request->get('code');
        if (isset($authCode)) {
            // Initialize the OAuth client

            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider($this->_config);

            try {
                // Make the token request
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                    'code' => $authCode,
                ]);

                // echo 'token : ' . $accessToken; echo '<br>';
                // echo 'token :: ' . $accessToken->getToken(); die;

                $graph = new Graph();

                $graph->setAccessToken($accessToken->getToken());

                $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();

                // vdebug($user->getSurname());
                // vdebug($user->getUserPrincipalName());

                if (empty($user->getUserPrincipalName())) {
                    return redirect('/login')->with('message', "Login gagal, anda tidak memiliki akses ke Aplikasi.");
                }

                $user_data = explode('@', $user->getUserPrincipalName());

                // dd($user_data);

                // $students_alumni = ['students.undip.ac.id','alumni.undip.ac.id'];
                $students = ['students.undip.ac.id'];

                if (in_array($user_data[1], $students)) {
                    // JIKA MAHASISWA

                    $mhs = UserHelp::mhs_get_record_by_nim($user->getSurname());

                    if (!empty($mhs)) {

                        $session_data = [
                            'username'             => $mhs->nim,
                            'nama_lengkap'      => strtoupper($mhs->nama),
                            'login_at'            => date('Y-m-d H:i:s'),
                            'login_as'            => 'MHS',
                            'role_as'            => 'MHS',
                        ];

                        $request->session()->put('session_data', $session_data);

                        return redirect('/dashboard');
                    } else {
                        return redirect('/')->with('message', "Login gagal, anda tidak memiliki akses ke Aplikasi.");
                    }
                }

                // JIKA SELAIN MHS

                $roles = UserHelp::admin_get_roles_by_nip($user->getSurname());

                if (empty($roles)) {
                    return redirect('/')->with('message', "Login gagal, anda tidak memiliki akses ke Aplikasi.");
                }

                $pegawai = UserHelp::admin_get_record_by_nip($user->getSurname());

                $session_data = [
                    'username'             => $pegawai->nip,
                    'nama_lengkap'      => $pegawai->glr_dpn . ' ' . $pegawai->nama . ' ' . $pegawai->glr_blkg,
                    'login_at'            => date('Y-m-d H:i:s'),
                    'login_as'            => 'ADMIN',
                    'role_as'            => null,
                ];

                $request->session()->put('session_data', $session_data);

                return redirect('/dashboard');
            } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                dd($e);
            } catch (Microsoft\Graph\Exception\GraphException $e) {
                dd($e);
            } catch (GuzzleHttp\Exception\RequestException $e) {
                dd($e);
            }
        }
    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login')->with('message', "Anda telah logout dari sistem.");
    }

    public function tes($nip)
    {
        dd($nip);
        UserHelp::admin_get_record_by_nip($nip);
    }
}
