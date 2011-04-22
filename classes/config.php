<?php
/**
 * User: Rado
 */
class Config {

    /**
     * Javascript style apply method.
     * Applies conf2 to conf1, overriding duplicate parameters if encountered
     * @static
     * @param  $conf1 - this argument is passed by reference and the applied results is stored in it
     * @param  $conf2 - this argument remains unchanged
     * @return void
     */
    public static function apply(&$conf1, $conf2) {
        foreach ($conf2 as $key => $value) {
            $conf1[$key] = $value;
        }
    }

    /**
     * Applies the rest of the arguments, passed to this method to the first one
     * Basic usage :
     * Config::apply_n($output_conf, $conf2, $conf3, $conf4, $conf5, ..., $confN); // $output_conf will have the result
     * @static
     * @param  $output_conf - the output assoc array, passed by reference, where the rest assoc arrays will be applied
     * @return void
     */
    public static function apply_n(&$output_conf) {
        if (func_num_args() == 1) {
            return; // we have nothing to apply
        }

        $other_configs = func_get_args(); // $other_configs[0] is $outputConf

        // apply all other configs to the requested one
        for ($i = 1 /*skip the first argument*/, $len = sizeof($other_configs); $i < $len; $i++) {
            self::apply($output_conf, $other_configs[$i]);
        }
    }
}