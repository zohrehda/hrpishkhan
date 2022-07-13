<?php

const FINAL_ACCEPT_ACTION = 'final_accept';
const PENDING_ACTION = 'pending';
const REJECT_ACTION = 'reject';
const ACCEPT_ACTION = 'accept';
const ASSIGN_ACTION = 'assign';
const CLOSE_ACTION = 'close';
const HOLD_ACTION = 'hold';
const OPEN_ACTION = 'open';

const PENDING_STATUS = 'pending';
const REJECTED_STATUS = 'rejected';
const ACCEPTED_STATUS = 'accepted';
const ASSIGNED_STATUS = 'assigned';
const CLOSED_STATUS = 'closed';
const HOLDING_STATUS = 'holding';


const ADMIN_FINAL_PENDING = 'admin_final_pending';

const ADMIN_PRIMARY_PENDING_PRG_STATUS = 'admin_primary_pending';
const ADMIN_FINAL_PENDING_PRG_STATUS = 'admin_final_pending';
const DETERMINERS_PENDING_PRG_STATUS = 'determiners_pending';

const PENDING_GROUP_STATUS = [
    DETERMINERS_PENDING_PRG_STATUS,
    ADMIN_PRIMARY_PENDING_PRG_STATUS,
    ADMIN_FINAL_PENDING_PRG_STATUS
];
