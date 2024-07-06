import {Controller} from "@hotwired/stimulus";
import {DataTable} from "simple-datatables"

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['table', 'tr'];
    static values = {
        search: true,
        fixedHeight: false,
        perPage: 10,
        filter: {type: String, default: ''},
        remoteUrl: {type: String, default: ''}
    }

    initalize() {
        this.initialized = false;
    }

    connect() {
        // super.connect();
        console.assert((this.perPageValue % 5) === 0, "per page must be divisible by 5");

        if (!this.initialized) {

            if (this.remoteUrlValue) {
                fetch(this.remoteUrlValue)
                    .then(response => response.json())
                    .then(data => {
                        if (!data || !data.length) {
                            return
                        }
                        // or passed in, even better
                        const headings = Object.keys(data[0]);
                        // const values = data.map(item => Object.values(item));

                        console.error(Object.keys(data[0]), data);
                        const dataTable = new DataTable(this.element, {
                            data: {
                                headings: headings,
                                data: data, // [values[0]],
                                // data: [], // data.map(item => Object.values(item))
                            }
                        })
                    })
            } else {
                const dataTable = new DataTable(this.element, {
                    searchable: this.searchValue,
                    fixedHeight: this.fixedHeightValue,
                    perPage: this.perPageValue,
                });
            }
        }

        this.initialized = true;


    }

    disconnect() {
        super.disconnect();
    }


}
