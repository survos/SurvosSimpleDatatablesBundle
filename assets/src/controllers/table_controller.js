import {Controller} from "@hotwired/stimulus";
import {DataTable} from "simple-datatables"

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['table', 'tr'];
    static values = {
        search: true,
        fixedHeight: false,
        perPage: 12,
        filter: {type: String, default: ''}
    }

    initalize() {
        this.initialized = false;
    }

    // this is never called, because data and class attributes in simple-datatables are removed during rendering.
    trTargetConnected(element) {
        console.log(element);

        // let countryCode = element.innerText;
        let countryCode = element.dataset.cc;
        element.innerHTML = 'xx';
    }


    connect() {
        // super.connect();
        console.log('hello from ' + this.identifier);
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
