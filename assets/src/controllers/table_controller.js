import {Controller} from "@hotwired/stimulus";
import {DataTable} from "simple-datatables"

// idea: use https://levelup.gitconnected.com/how-to-transfer-large-json-files-efficiently-08c4b83ee058 to read large JSON files

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['table', 'tr'];
    static values = {
        search: true,
        fixedHeight: false,
        perPage: 10,
        columns: {type: String, default: '[]'},
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
                        let headings = JSON.parse(this.columnsValue);
                        if (headings.length === 0) {
                            headings = Object.keys(data[0]);
                        }

                        // headings = JSON.parse('["quell_id","marking"]');

                        // const values = data.map(item => Object.values(item));

                        console.error(headings, data[0]);
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
