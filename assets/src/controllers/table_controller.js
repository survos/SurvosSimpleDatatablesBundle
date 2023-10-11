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
        const dataTable = new DataTable(this.element, {
            searchable: true,
            fixedHeight: true,
        });
        this.initialized = true;
    }

    disconnect() {
        super.disconnect();
    }


}
