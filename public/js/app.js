/**
 * Add jQuery Validation plugin method for a valid password
 *
 * Valid passwords contain at least one letter and one number.
 */
$.validator.addMethod('validPassword',
    function(value, element, param) {

        if (value != '') {
            if (value.match(/.*[a-z]+.*/i) == null) {
                return false;
            }
            if (value.match(/.*\d+.*/) == null) {
                return false;
            }
            if (value.match(/.*\W+.*/i) == null) {
                return false;
            }
        }

        return true;
    },
    'Hasło musi zawierać co najmniej jedną literę, jedną cyfrę i jeden znak specjalny.'
);
