import {Controller} from "@hotwired/stimulus";

import {DataTable} from "simple-datatables"

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['table', 'modal', 'modalBody', 'fieldSearch', 'message'];
    static values = {
        search: true,
        info: false,
        filter: {type: String, default: ''}
    }

    initalize() {
        this.initialized = false;
    }

    connect() {
        // super.connect();
        console.error('hello from ' + this.identifier);
        const myTable = document.querySelector(this.element);
        const dataTable = new DataTable(myTable, {
            searchable: false,
            fixedHeight: true,
        });
        this.initialized = true;
    }

    disconnect() {
        super.disconnect();
    }


}
