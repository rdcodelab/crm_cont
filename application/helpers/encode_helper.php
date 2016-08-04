<?php
 /**
 * Codifica uma string como base64 para uso em um URI CI.
 *
 * @param string $str String ou Array a ser codificado
 * @return string
 */
function url_base64_encode(&$str = "") {
    return strtr(
                    base64_encode($str), array(
                '+' => '.',
                '=' => '-',
                '/' => '~'
                    )
    );
}
 
/**
 * Decodifica uma string base64 que foi codificado por url_base64_encode.
 *
 * @param string $str A seq��ncia de caracteres base64 para decodificar.
 * @return object
 */
function url_base64_decode(&$str = "") {
    return base64_decode(strtr(
                            $str, array(
                        '.' => '+',
                        '-' => '=',
                        '~' => '/'
                            )
                    ));
}
 
// End of file: encode_helper.php
// Location: ./system/application/helpers/encode_helper.php