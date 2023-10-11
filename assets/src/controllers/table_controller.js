import {Controller} from "@hotwired/stimulus";
import {DataTable} from "simple-datatables"

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['table', 'modal', 'modalBody', 'fieldSearch', 'message'];
    static values = {
        search: true,
        fixedHeight: false,
        perPage: 12,
        filter: {type: String, default: ''}
    }

    initalize() {
        this.initialized = false;
    }

    connect() {
        // super.connect();
        console.error('hello from ' + this.identifier);
        const dataTable = new DataTable(this.element, {
            searchable: this.searchValue,
            fixedHeight: this.fixedHeightValue,
            perPage: this.perPageValue,
        });
        this.initialized = true;
    }

    disconnect() {
        super.disconnect();
    }


}
