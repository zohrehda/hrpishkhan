function matchStart(params, data) {
    params.term = params.term || "";
    if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
        return data;
    }
    return false;
}
