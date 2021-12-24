<?php

namespace App;

class RequisitionStatus
{
    const PENDING_STATUS = 'pending';
    const ACCEPTED_STATUS = 'accepted';
    const ASSIGN_STATUS = 'assigned';
    const CLOSED_STATUS = 'closed';
    const HOLDING_STATUS = 'holding';
    const OPEN_STATUS = 'open';
    const REJECTED_STATUS = 'rejected';
    const ADMIN_PRIMARY_PENDING = 'admin_primary_pending';
    const ADMIN_FINAL_PENDING = 'admin_final_pending';
    const DETERMINERS_PENDING = 'determiners_pending';
    const PENDING_GROUP=[
        self::DETERMINERS_PENDING ,
        self::ADMIN_PRIMARY_PENDING ,
        self::ADMIN_FINAL_PENDING
    ] ;

}
