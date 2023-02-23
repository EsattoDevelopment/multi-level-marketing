<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Crypt;
use Google2FA;
use Validator;
use Illuminate\Http\Request;
use ParagonIE\ConstantTime\Base32;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class Google2FAController.
 */
class Google2FAController extends Controller
{
    use ValidatesRequests;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => 'required|digits:6',
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        if (! empty($request->user()->google2fa_secret)) {
            flash()->info('Autenticação de 2 fatores já está ativa na sua conta!');

            return redirect()->route('dados-usuario.seguranca');
        }

        //generate new secret
        $secret = session('2fa:user:verifySecret') ? session('2fa:user:verifySecret') : $this->generateSecret();

        $request->session()->put('2fa:user:verifySecret', $secret);

        //get user
        $user = $request->user();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            env('GOOGLE_AUTHENTICATOR_NAME'),
            $user->email,
            $secret,
            300
        );

        return view('default.2fa/enableTwoFactor', ['image' => $imageDataUri,
            'secret' => $secret, ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function verifyEnabledTwoFactor(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if (Google2FA::verifyKey(session('2fa:user:verifySecret'), $request->get('code'))) {
            $user = $request->user();

            // encrypt and then save secret
            $user->google2fa_secret = Crypt::encrypt(session('2fa:user:verifySecret'));
            $user->save();
            session()->remove('2fa:user:verifySecret');

            flash()->success('Autenticação de 2 fatores ativada com sucesso!');

            return redirect()->route('dados-usuario.seguranca');
        }

        flash()->error('Token não é válido, tente novamente.');

        return back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        if (empty($request->user()->google2fa_secret)) {
            flash()->info('Autenticação de 2 fatores não está ativa em sua conta!');

            return redirect()->route('dados-usuario.seguranca');
        }

        return view('default.2fa/disableTwoFactor');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyDisabledTwoFactor(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if (Google2FA::verifyKey(Crypt::decrypt($request->user()->google2fa_secret), $request->get('code'))) {
            $user = $request->user();
            $user->google2fa_secret = null;
            $user->save();
            flash()->success('Autenticação de 2 fatores removida com sucesso!');

            session()->remove('2fa:user:verifySecret');

            return redirect()->route('dados-usuario.seguranca');
        }

        flash()->error('Token não é válido, tente novamente.');

        return back();
    }

    /**
     * Generate a secret key in Base32 format.
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes);
    }
}
