(function() {
    /* global fetch, $ */

    /**************************************************************************/
    /* variables 'globales' de la función                                     */

    let lastPage = '';
    let pageNumber = 1;
    let route = '';
    let rows = 3;
    let token = '';

    /**************************************************************************/
    /* acciones que se ejecutan inmediatamente   */
    
    //evento clic del botón addMoneda
    let addMoneda = document.getElementById('addMoneda');
    if(addMoneda) {
        addMoneda.addEventListener('click', function(event) {
            addCurrency();
        });
    }
    
    let tbody = document.getElementById('tbody');
    tbody.addEventListener('click', function(event) {
        if (event.target.classList.contains('editModal')) {
            document.body.classList.add('body');
            setTimeout(function() { getMoneda(event.target.dataset.id); }, 200);
        }
        
        if(event.target.classList.contains('deleteModal')) {
            document.getElementById('nameBorrar').innerHTML = event.target.dataset.name;
            route = 'ajaxmoneda/' + event.target.dataset.id;
            $('#deleteModal').modal('show');
        }
    });
    
    //evento click del botón editEmpresa
    let editMoneda = document.getElementById('editMoneda');
    if(editMoneda) {
        editMoneda.addEventListener('click', function(event) {
            editCurrency();
        });
    }
    
    //evento click del botÃ³n deleteEmpresa
    let deleteMoneda = document.getElementById('deleteMoneda');
    if (deleteMoneda) {
        deleteMoneda.addEventListener('click', function(event) {
            deleteCurrency();
        });
    }

    function deleteChildNodes(id) {
        let element = document.getElementById(id);
        //element.innerHTML = '';
        if(element) {
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }
        }
    }

    //petición ajax de la página con el listado de empresas paginado
    function getPage(page) {
        lastPage = page;
        fetch(page)
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                console.log(json);
                createTableDom(json);
                createLinkDom(json);
                token = json.token;
                pageNumber = json.monedas.current_page;
            })
            .catch(function(error) {
                alert('error');
                console.log('Request failed', error)
            });
    }

    //petición ajax para obtener datos de la empresa que se va a editar
    function getMoneda(id) {
        fetch('ajaxmoneda/' + id)
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                document.body.classList.remove('body');
                console.log(json);
                if (json.moneda.id) {
                    document.getElementById('name').value = json.moneda.name;
                    document.getElementById('symbol').value = json.moneda.symbol;
                    document.getElementById('country').value = json.moneda.country;
                    document.getElementById('change').value = json.moneda.change;
                    route = 'ajaxmoneda/' + json.moneda.id;
                    $('#editModal').modal('show');
                }
                else {
                    alert('Error, la empresa ya no existe.');
                }
            })
            .catch(function(error) {
                document.body.classList.remove('body');
                alert('error');
                console.log('Request failed', error)
            });
    }

    //convierte los datos del formulario FormData en parámetros de la petición
    function getFormdata(idForm = '') {
        let data = '';
        if(idForm != '') {
            let form = document.getElementById(idForm);
            if(form) {
                let formData = new FormData(form); //multipart/form-data
                for(var entry of formData.entries()) {
                    data += encodeURIComponent(entry[0]) + '=' + encodeURIComponent(entry[1]) + '&';
                }    
            }
        }
        data += '_page=' + pageNumber + '&';
        data += '_token=' + token;
        return data;
    }

    function editCurrency() {
        let data = getFormdata('monedaForm');
        let url = route;
        document.body.classList.add('body');
        fetch(url, {
                body: data,
                headers: {
                    "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
                },
                method: 'put',
            })
            .then(function(response) {
                document.body.classList.remove('body');
                return response.json();
            })
            .then(function(data) {
                // if(!data.notValid) {
                //     //mostrar errores
                // } else {
                actualizarMoneda(data);
                $('#editModal').modal('hide');
                //}
                console.log('Request succeeded with JSON response', data);
            })
            .catch(function(error) {
                console.log('Request failed', error);
            });
    }

    //peticion ajax para agregar una empresa nueva
    function addCurrency() {
        let data = getFormdata('addMonedaForm');
        console.log('getFormdata', data);
        let url = 'ajaxmoneda';
        document.body.classList.add('body');
        fetch(url, {
                body: data,
                headers: {
                    "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
                },
                method: 'post',
            })
            .then(function(response) {
                document.body.classList.remove('body');
                return response.json();
            })
            .then(function(data) {
                mostrarMoneda(data);
                $('#addModal').modal('hide');
                let form = document.getElementById('addMonedaForm');
                form.reset();
                console.log('Request succeeded with JSON response', data);
            })
            .catch(function(error) {
                console.log('Request failed', error);
            });
    }

    //petición ajax para obtener datos de la empresa que se va a borrar
    function deleteCurrency() {
        let url = route;
        document.body.classList.add('body');
        fetch(url, {
                body: getFormdata() + "&lastPage=" + encodeURIComponent(lastPage),
                headers: {
                    "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
                },
                method: 'delete',
            })
            .then(function(response) {
                document.body.classList.remove('body');
                return response.json();
            })
            .then(function(data) {
                //no controlamos los errores, habrÃa que hacerlo
                //if(!data.notValid) {
                //mostrar errores
                //} else {
                //actualizarEmpresa(data);
                $('#deleteModal').modal('hide');
                createTableDom(data);
                createLinkDom(data);
                getPage(lastPage);
                //}
                console.log('Request succeeded with JSON response', data);
            })
            .catch(function(error) {
                console.log('Request failed', error);
            });
    }

    function actualizarMoneda(data) {
        let id = data.moneda.id;
        document.getElementById('td' + id + "_0").textContent = data.moneda.name;
        document.getElementById('td' + id + "_1").firstChild.nodeValue = data.moneda.symbol;
        document.getElementById('td' + id + "_2").textContent = data.moneda.country;
        document.getElementById('td' + id + "_3").textContent = data.moneda.change;
    }

    getPage('ajaxmoneda');

    function createLinkDom(jsonComplete) {
        deleteChildNodes('enlacesPaginacion');
        let enlaces = document.getElementById('enlacesPaginacion');
        let json = jsonComplete.monedas;
        for (let i = 0; i < json.links.length; i++) {
            let enlace = createLink(json.links[i]);
            enlaces.appendChild(enlace);
        }
    }

    // function createLink(json) {
    //     let li;
    //     if (json.active) {
    //         //enlace actual
    //         //<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
    //         li = document.createElement('li');
    //         li.classList.add('page-item');
    //         li.classList.add('active');
    //         li.setAttribute('aria-current', 'page');
    //         //li.setAttribute('aria-label', json.label);
    //         let span = document.createElement('span');
    //         span.classList.add('page-link');
    //         //span.setAttribute('aria-hidden', 'true');
    //         let node = document.createTextNode(decodeLabel(json.label));
    //         span.appendChild(node);
    //         li.appendChild(span);
    //     }
    //     else {
    //         if (json.url) {
    //             //enlace que existe
    //             //<li class="page-item"><a class="page-link" href="URL">LABEL</a></li>
    //             li = document.createElement('li');
    //             li.classList.add('page-item');
    //             li.classList.add('pointer');
    //             let a = document.createElement('a');
    //             li.classList.add('page-link');
    //             a.dataset.url = json.url;
    //             a.addEventListener('click', function(event) {
    //                 getPage(event.target.dataset.url);
    //             });
    //             let node = document.createTextNode(decodeLabel(json.label));
    //             a.appendChild(node);
    //             li.appendChild(a);
    //         }
    //         else {
    //             //enlace que no existe
    //             //<li class="page-item disabled" aria-disabled="true" aria-label="LABEL"><span class="page-link" aria-hidden="true">LABEL</span></li>
    //             li = document.createElement('li');
    //             li.classList.add('page-item');
    //             li.classList.add('disabled');
    //             li.setAttribute('aria-disables', 'true');
    //             li.setAttribute('aria-label', json.label);
    //             let span = document.createElement('span');
    //             span.classList.add('page-link');
    //             span.setAttribute('aria-hidden', 'true');
    //             let node = document.createTextNode(decodeLabel(json.label));
    //             span.appendChild(node);
    //             li.appendChild(span);
    //         }
    //     }
    //     return li;
    // }
    
    //crea los enlaces de paginación
    function createLink(json) {
        let li;
        if(json.active) {
            //enlace actual
            //<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
            li = createLinkActual(json);
        } else {
            if(json.url) {
                //enlace que existe
                //<li class="page-item"><a class="page-link" href="URL">LABEL</a></li>
                li = createLinkPage(json);
            } else {
                //enlace que no existe
                //<li class="page-item disabled" aria-disabled="true" aria-label="LABEL"><span class="page-link" aria-hidden="true">LABEL</span></li>
                li = createLinkDisabled(json);
            }
        }
        return li;
    }
    
    //enlace de paginación a la página actual
    function createLinkActual(json) {
        let li = document.createElement('li');
        li.classList.add('page-item');
        li.classList.add('active');
        li.setAttribute('aria-current', 'page');
        let span = document.createElement('span');
        span.classList.add('page-link');
        let node = document.createTextNode(decodeLabel(json.label));
        span.appendChild(node);
        li.appendChild(span);
        return li;
    }
    
    //enlace de paginación deshabilitado
    function createLinkDisabled(json) {
        let li = document.createElement('li');
        li.classList.add('page-item');
        li.classList.add('disabled');
        li.setAttribute('aria-disables', 'true');
        li.setAttribute('aria-label', json.label);
        let span = document.createElement('span');
        span.classList.add('page-link');
        span.setAttribute('aria-hidden', 'true');
        let node = document.createTextNode(decodeLabel(json.label));
        span.appendChild(node);
        li.appendChild(span);
        return li;
    }
    
    //enlace de paginación a una página que existe
    function createLinkPage(json) {
        let li = document.createElement('li');
        li.classList.add('page-item');
        li.classList.add('pointer');
        let a = document.createElement('a');
        li.classList.add('page-link');
        a.dataset.url = json.url;
        a.dataset.page = json.label;
        a.addEventListener('click', function(event) {
            event.preventDefault();
            getPage(event.target.dataset.url);
        });
        let node = document.createTextNode(decodeLabel(json.label));
        a.appendChild(node);
        li.appendChild(a);
        return li;
    }

    function createTableDom(jsonComplete) {
        deleteChildNodes('tbody');
        let tbody = document.getElementById('tbody');
        let json = jsonComplete.monedas;
        for (let i = 0; i < json.data.length; i++) {
            // let tr = document.createElement('tr');
            // let id = json.data[i].id;
            // tr.appendChild(createTd(json.data[i].id));
            // tr.appendChild(createTd(json.data[i].name, [], [{ name: 'name', value: i + '_' + 0 }], id + '_' + 0));
            // tr.appendChild(createTd(json.data[i].symbol, [], [{ name: 'name', value: i + '_' + 1 }], id + '_' + 1));
            // tr.appendChild(createTd(json.data[i].country, [], [{ name: 'name', value: i + '_' + 2 }], id + '_' + 2));
            // tr.appendChild(createTd(json.data[i].change, [], [{ name: 'name', value: i + '_' + 3 }], id + '_' + 3));
            // tr.appendChild(createTd('view', [], [{ name: 'id', value: json.data[i].id }]));
            // tr.appendChild(createTd('add', [], ));
            // tr.appendChild(createTd('show', [], ));
            // tr.appendChild(createTd('edit', ['pointer', 'azul', 'editModal'], [{ name: 'id', value: json.data[i].id },
            //     { name: 'name', value: json.data[i].name },
            //     { name: 'symbol', value: json.data[i].symbol },
            //     { name: 'country', value: json.data[i].country },
            //     { name: 'change', value: json.data[i].change },
            // ]));
            
            // tr.appendChild(createTd('delete', ['pointer', 'azul', 'deleteModal'], [{name: 'id', value: id},
            //                                                                   {name: 'name', value: json.name},]));
            tbody.appendChild(createTr(json.data[i]));
        }
    }

    function createTd(text, classNames = [], data = [], id = '') {
        let td = document.createElement('td');
        let node = document.createTextNode(text);
        td.appendChild(node);
        if (id != '') {
            td.id = 'td' + id;
        }
        for (const className of classNames) {
            td.classList.add(className);
        }
        for (const dataAttribute of data) {
            td.dataset[dataAttribute.name] = dataAttribute.value;
        }
        return td;
    }

    //para decodificar los enlaces de página anterior y siguiente de forma correcta
    //son estos dos símbolos que daban problemas: « y »
    function decodeLabel(jsonLabel) {
        var textarea = document.createElement('textarea');
        textarea.innerHTML = jsonLabel;
        return textarea.value;
    }
    
    function mostrarMoneda(data) {
        // console.log('mostrar moneda', data);
        let tr = createTr(data.moneda);
        let tbody = document.getElementById('tbody');
        tbody.appendChild(tr);
    }
    
    function createTr(json) {
        let tr = document.createElement('tr');
            let id = json.id;
            tr.appendChild(createTd(id));
            tr.appendChild(createTd(json.name, [], [{ name: 'name', value: id + '_' + 0 }], id + '_' + 0));
            tr.appendChild(createTd(json.symbol, [], [{ name: 'name', value: id + '_' + 1 }], id + '_' + 1));
            tr.appendChild(createTd(json.country, [], [{ name: 'name', value: id + '_' + 2 }], id + '_' + 2));
            tr.appendChild(createTd(json.change, [], [{ name: 'name', value: id + '_' + 3 }], id + '_' + 3));
            // tr.appendChild(createTd('view', [], [{ name: 'id', value: id }]));
            // tr.appendChild(createTd('add', [], ));
            // tr.appendChild(createTd('show', [], ));
            tr.appendChild(createTd('edit', ['pointer', 'azul', 'editModal'], [{ name: 'id', value: id },
                { name: 'name', value: json.name },
                { name: 'symbol', value: json.symbol },
                { name: 'country', value: json.country },
                { name: 'change', value: json.change },
            ]));
            
            tr.appendChild(createTd('delete', ['pointer', 'azul', 'deleteModal'], [{name: 'id', value: id},
                                                                               {name: 'name', value: json.name},]));
            tbody.appendChild(tr);
        
        return tr;
    }

})();

//https://stackoverflow.com/questions/15995813/best-way-to-bind-events-with-dynamiac-data-to-elements-after-ajax-load-in-raw

/*$('#editModal').modal('show');
            document.getElementById('name').value = event.target.dataset.name;
            document.getElementById('phone').value = event.target.dataset.phone;
            document.getElementById('address').value = event.target.dataset.address;
            document.getElementById('taxnumber').value = event.target.dataset.taxnumber;
            document.getElementById('conctactperson').value = event.target.dataset.conctactperson;*/

/*function pintaEnlace(json) {
       let text = '<a id="e1" class="getpage" data-url="' + json.next_page_url + '" href="javascript: void(0)">siguiente pÃ¡gina</a>';
       document.getElementById('enlacesPaginacion').innerHTML = text;
       document.getElementById('e1').addEventListener('click', function(event) {
           getPage(event.target.dataset.url);
       });
   }
   
   function pintaTabla(json) {
       let text = '';
       for(let i = 0; i < json.data.length; i++) {
           text += '<tr><td>' +
                   json.data[i].id +
                   '</td><td>' +
                   json.data[i].name +
                   '</td><td>' +
                   json.data[i].phone +
                   '</td><td>' +
                   json.data[i].conctactperson +
                   '</td><td>' +
                   json.data[i].taxnumber +
                   '</td><td>enlace</td><td>enlace</td><td>enlace</td><td>enlace</td><td>enlace</td></tr>';
       }
       //document.getElementById('tbody').innerHTML = text;
       deleteChildNodes('tbody');
       document.getElementById('tbody').insertAdjacentHTML('afterbegin', text);
   }*/

/*function createLinkDomOld(json) {
    let ahref = document.createElement('a');
    ahref.classList.add('getPage'); //ahref.setAttribute('class', 'getPage');
    ahref.setAttribute('href', 'javascript: void(0)');
    ahref.dataset.url = json.next_page_url;
    let node = document.createTextNode('siguiente pÃ¡gina');
    ahref.appendChild(node);
    deleteChildNodes('enlacesPaginacion');
    ahref.addEventListener('click', function(event) {
        getPage(event.target.dataset.url);
    });
    let enlaces = document.getElementById('enlacesPaginacion');
    enlaces.appendChild(ahref);
}*/