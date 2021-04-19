<?php

namespace app\src\Constant;

class ErrorCode
{
    // success
    const CODE_SUCCESS = 'SUCCESS';

    // empty
    const CODE_EMPTY = 'ZERO';

    // not allowed
    const CODE_NOT_ALLOWED = 'NALW';

    // expired token API
    const CODE_EXPIRED = 'AUTH-01';

    // wrong token API format
    const CODE_WRONG_TOKEN = 'AUTH-02';

    // unknown
    const CODE_UNKNOWN = 'NFIG';

    // bad request
    const CODE_BAD_REQUEST = 'NREQ';

    // error on server
    const CODE_SERVER_FAIL = 'STRGDY-01';

    // error on fetch other server
    const CODE_OTHER_SERVER_FAIL = 'STRGDY-02';
}