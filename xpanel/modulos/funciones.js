// Funciones extras
// ------------------------------

// permite definir el valor a seleccionar en un elemento SELECT
export function setSelectedIndex(s, valsearch) {
    let i
    for (i = 0; i < s.options.length; i++) {
        if (s.options[i].value == valsearch) {
            s.options[i].selected = true;
            break;
        }
    }

    return;
}

// obtengo y asigno los valores de autocomplete
export function getAutocomplete(url, form) {
    return new Promise(function(resolve, reject) {
        fetch(url)
            .then(response => response.json())
            .then(result => {

                Object.keys(result).forEach(campo => {
                    let opt = '';
                    result[campo].forEach(valor => {
                        if (Object.keys(valor).length == 1) {
                            Object.keys(valor).forEach(item => {
                                opt += '<option value="' + valor[item] + '" />'
                            })

                        } else {
                            opt += '<option value="' + valor[Object.keys(valor)[0]] + '">' + valor[Object.keys(valor)[1]] + '</option>'

                        }

                    })

                    if (form.querySelector('#' + campo).list) {
                        form.querySelector('#' + campo).list.innerHTML = opt
                    } else {
                        opt = '<option value="">' + form.querySelector('#' + campo).title + '</option>' + opt
                        form.querySelector('#' + campo).innerHTML = opt
                    }

                })

            })
            .catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message));
    })
};

export function createElement(tag, className) {
    const element = document.createElement(tag)

    if (className) {
        const lista = className.split(' ')
        if (lista.length > 1) {
            lista.forEach(item => {
                element.classList.add(item)
            })
        } else {
            element.classList.add(className)
        }
    }

    return element
}

// ----------------------------------

export function jsModalMessage(content, callback) {
    let result = ''

    let containerModal = createElement('div', 'modal face')
    containerModal.id = 'modalMessage'
    containerModal.tabindex = -1
    containerModal.setAttribute('role', 'dialog')
    containerModal.setAttribute('aria-hidden', true)
    containerModal.setAttribute('data-backdrop', 'static')
    containerModal.setAttribute('data-keyboard', false)
    containerModal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>${content.title}</h5>
                </div>
                <div class="modal-body">
                    <p>${content.message}</p>
                </div>                            
                
                <div class="modal-footer">
                    <div id="responseModal" class="flex-grow-1"></div>
                    <button type='button' class='btn btn-message-cancel btn-outline-secondary w-25'>Cancelar</button>
                    <button type='submit' class='btn btn-message-ok btn-primary w-25'>Aceptar</button>
                </div>

            </div>
        </div>
    `

    document.body.append(containerModal)
    let myModalInstance = new Modal(containerModal)
    myModalInstance.show()

    let btnOk = document.querySelector('.btn-message-ok')
    let btnCancel = document.querySelector('.btn-message-cancel')

    switch (content.type) {
        case "alert":
        case "information":
            btnOk.innerText = 'Aceptar'
            btnCancel.style.display = 'none'
            break;

        case "confirm":
            btnOk.innerText = 'SI'
            btnCancel.innerText = 'NO'
            break;

        case "prompt":
            break;
    }

    /*
    containerModal.addEventListener('show.bs.modal', function(event){
        console.log('show.bs.modal',result);        
    }, false);
    */

    containerModal.addEventListener('hide.bs.modal', (event) => { callback(result) }, false);

    document.querySelector('.btn-message-ok').addEventListener('click', (e) => {
        result = true
        closeModalMessage()
    })

    document.querySelector('.btn-message-cancel').addEventListener('click', (e) => {
        result = false
        closeModalMessage()
    })

    function closeModalMessage() {
        myModalInstance.hide()
        const containerModal = document.getElementById('modalMessage')
        containerModal.remove()
        myModalInstance = null
    }

}

export function jsModalImagen(url, caption = "") {
    let containerModal = document.createElement('div');
    containerModal.className = 'containerModal';
    containerModal.id = 'containerModal';
    containerModal.style.paddingTop = '100px';
    containerModal.innerHTML = `
        <span class="closeModal">&times;</span>
    `;

    let contentModal = document.createElement('img');
    contentModal.className = 'contentModalImagen';
    contentModal.src = url;

    containerModal.appendChild(contentModal);

    if (caption != "") {
        let captionImagen = document.createElement('div');
        captionImagen.className = 'captionImagen';
        captionImagen.innerHTML = caption;
        containerModal.appendChild(captionImagen);
    }

    document.body.appendChild(containerModal);

    document.querySelector('.closeModal').addEventListener('click', (e) => {
        let containerModal = document.getElementById('containerModal');
        document.body.removeChild(containerModal);
    })

}

export function jsModalDialog(url, width, data) {
    let containerModal = document.createElement('div');
    containerModal.className = 'containerModal';
    containerModal.id = 'containerModal';

    let contentModal = document.createElement('div');
    contentModal.className = 'contentModal';
    contentModal.id = 'contentModal';
    contentModal.style.width = width + 'vw';

    fetch(url)
        .then(response => response.text())
        .then(result => {
            contentModal.innerHTML = result;
            contentModal.querySelector('#closeModal').onclick = jsCloseModalDialog;

        })
        .catch(error => console.log('Hubo un problema con la petición Fetch: ' + error.message));


    containerModal.appendChild(contentModal);
    document.body.appendChild(containerModal);
}

function jsCloseModalDialog() {
    let containerModal = document.getElementById('containerModal');
    document.body.removeChild(containerModal);
}

export function clearResponse() {
    document.querySelector('#response').innerHTML = '';
}

export function changePageTitle(page_title) {
    // change page title
    document.querySelector('#page-title').innerText = page_title;

    // change title tag
    document.title = page_title;
}

export function getUrlVars() {
    var vars = [],
        hash;
    var hashes = window.location.href.slice(window.location.href.indexOf("?") + 1).split("&");
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split("=");
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

export function getLinkVars(href) {
    var vars = [],
        hash;
    var hashes = href.slice(href.indexOf("?") + 1).split("&");
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split("=");
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

export function AbrirURL(t) {
    if ($(t).val()) {
        window.location = $(t).val();
    }
}

export function redondear(sVal, nDec) {
    var n = parseFloat(sVal);
    var s = "0.00";
    if (!isNaN(n)) {
        n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
        s = String(n);
        s +=
            (s.indexOf(".") == -1 ? "." : "") + String(Math.pow(10, nDec)).substr(1);
        s = s.substr(0, s.indexOf(".") + nDec + 1);
    }
    return s;
}

export function number_format(number, decimals = 3, dec_point = ',', thousands_sep = '.') {
    number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
}

export function daysBetween(date1, date2) {
    if (date1.indexOf("-") != -1) {
        date1 = date1.split("-");
    } else if (date1.indexOf("/") != -1) {
        date1 = date1.split("/");
    } else {
        return 0;
    }
    if (date2.indexOf("-") != -1) {
        date2 = date2.split("-");
    } else if (date2.indexOf("/") != -1) {
        date2 = date2.split("/");
    } else {
        return 0;
    }
    if (parseInt(date1[0], 10) >= 1000) {
        var sDate = new Date(date1[0] + "/" + date1[1] + "/" + date1[2]);
    } else if (parseInt(date1[2], 10) >= 1000) {
        var sDate = new Date(date1[2] + "/" + date1[1] + "/" + date1[0]);
    } else {
        return 0;
    }
    if (parseInt(date2[0], 10) >= 1000) {
        var eDate = new Date(date2[0] + "/" + date2[1] + "/" + date2[2]);
    } else if (parseInt(date2[2], 10) >= 1000) {
        var eDate = new Date(date2[2] + "/" + date2[1] + "/" + date2[0]);
    } else {
        return 0;
    }

    var one_day = 1000 * 60 * 60 * 24;
    var daysApart = Math.abs(
        Math.ceil((sDate.getTime() - eDate.getTime()) / one_day)
    );
    return daysApart;
}

export function htmlEncode(value) {
    return $("<div />")
        .text(value)
        .html();
}

export function htmlDecode(value) {
    return $("<div />")
        .html(value)
        .text();
}

export function remover_acentos(str) {
    let map = { 'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C', 'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I', 'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ý': 'Y', 'ß': 's', 'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c', 'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ý': 'y', 'ÿ': 'y', 'Ā': 'A', 'ā': 'a', 'Ă': 'A', 'ă': 'a', 'Ą': 'A', 'ą': 'a', 'Ć': 'C', 'ć': 'c', 'Ĉ': 'C', 'ĉ': 'c', 'Ċ': 'C', 'ċ': 'c', 'Č': 'C', 'č': 'c', 'Ď': 'D', 'ď': 'd', 'Đ': 'D', 'đ': 'd', 'Ē': 'E', 'ē': 'e', 'Ĕ': 'E', 'ĕ': 'e', 'Ė': 'E', 'ė': 'e', 'Ę': 'E', 'ę': 'e', 'Ě': 'E', 'ě': 'e', 'Ĝ': 'G', 'ĝ': 'g', 'Ğ': 'G', 'ğ': 'g', 'Ġ': 'G', 'ġ': 'g', 'Ģ': 'G', 'ģ': 'g', 'Ĥ': 'H', 'ĥ': 'h', 'Ħ': 'H', 'ħ': 'h', 'Ĩ': 'I', 'ĩ': 'i', 'Ī': 'I', 'ī': 'i', 'Ĭ': 'I', 'ĭ': 'i', 'Į': 'I', 'į': 'i', 'İ': 'I', 'ı': 'i', 'Ĳ': 'IJ', 'ĳ': 'ij', 'Ĵ': 'J', 'ĵ': 'j', 'Ķ': 'K', 'ķ': 'k', 'Ĺ': 'L', 'ĺ': 'l', 'Ļ': 'L', 'ļ': 'l', 'Ľ': 'L', 'ľ': 'l', 'Ŀ': 'L', 'ŀ': 'l', 'Ł': 'L', 'ł': 'l', 'Ń': 'N', 'ń': 'n', 'Ņ': 'N', 'ņ': 'n', 'Ň': 'N', 'ň': 'n', 'ŉ': 'n', 'Ō': 'O', 'ō': 'o', 'Ŏ': 'O', 'ŏ': 'o', 'Ő': 'O', 'ő': 'o', 'Œ': 'OE', 'œ': 'oe', 'Ŕ': 'R', 'ŕ': 'r', 'Ŗ': 'R', 'ŗ': 'r', 'Ř': 'R', 'ř': 'r', 'Ś': 'S', 'ś': 's', 'Ŝ': 'S', 'ŝ': 's', 'Ş': 'S', 'ş': 's', 'Š': 'S', 'š': 's', 'Ţ': 'T', 'ţ': 't', 'Ť': 'T', 'ť': 't', 'Ŧ': 'T', 'ŧ': 't', 'Ũ': 'U', 'ũ': 'u', 'Ū': 'U', 'ū': 'u', 'Ŭ': 'U', 'ŭ': 'u', 'Ů': 'U', 'ů': 'u', 'Ű': 'U', 'ű': 'u', 'Ų': 'U', 'ų': 'u', 'Ŵ': 'W', 'ŵ': 'w', 'Ŷ': 'Y', 'ŷ': 'y', 'Ÿ': 'Y', 'Ź': 'Z', 'ź': 'z', 'Ż': 'Z', 'ż': 'z', 'Ž': 'Z', 'ž': 'z', 'ſ': 's', 'ƒ': 'f', 'Ơ': 'O', 'ơ': 'o', 'Ư': 'U', 'ư': 'u', 'Ǎ': 'A', 'ǎ': 'a', 'Ǐ': 'I', 'ǐ': 'i', 'Ǒ': 'O', 'ǒ': 'o', 'Ǔ': 'U', 'ǔ': 'u', 'Ǖ': 'U', 'ǖ': 'u', 'Ǘ': 'U', 'ǘ': 'u', 'Ǚ': 'U', 'ǚ': 'u', 'Ǜ': 'U', 'ǜ': 'u', 'Ǻ': 'A', 'ǻ': 'a', 'Ǽ': 'AE', 'ǽ': 'ae', 'Ǿ': 'O', 'ǿ': 'o' };
    let res = '';
    for (var i = 0; i < str.length; i++) {
        let c = str.charAt(i);
        res += map[c] || c;
    }
    return res;
}

/* GRAFICAR */
/* --------------------------------------------------------- */

function genColor() {
    let o = Math.round,
        r = Math.random,
        s = 255;
    let t = 1; // r().toFixed(1)
    let vColor = 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + t + ')';
    return vColor;
}

function asignarColor(indice) {
    let listaColores = ['#BCE02E', '#E0642E', '#E0D62E', '#2E97E0', '#B02EE0', '#E02E75', '#5CE02E', '#E0B02E', '#4074C8', '#C00000', '#FF0000', '#FFC000', '#FFFF00', '#92D050', '#00B050', '#00B0F0', '#0070C0', '#002060', '#7030A0', '#984807', '#E46C0A', '#FAC090', '#FCD5B5', '#FDEADA', '#F79646', '#4BACC6', '#DBEEF4', '#B7DEE8', '#93CDDD', '#31859C', '#215968', '#403152', '#604A7B', '#B3A2C7', '#CCC1DA', '#E6E0EC', '#8064A2', '#9BBB59', '#EBF1DE', '#D7E4BD', '#C3D69B', '#77933C', '#4F6228', '#632523', '#953735', '#D99694', '#E6B9B8', '#F2DCDB', '#C0504D', '#4F81BD', '#DCE6F2', '#B9CDE5', '#95B3D7', '#376092', '#254061', '#10253F', '#17375E', '#558ED5', '#8EB4E3', '#C6D9F1', '#1F497D', '#EEECE1', '#DDD9C3', '#C4BD97', '#948A54', '#4A452A'];
    if (indice > 66) {
        return listaColores[indice - 66];
    } else {
        return listaColores[indice];
    }
}

export function graficarChartJS(elemento, tipoGrafico, etiquetas, datos, titulos, colorAleatorio) {
    let myChart;

    datos.forEach(element => element.fill = true);
    switch (tipoGrafico) {
        case "bar":
        case "horizontalBar":
            if (datos.length > 1) {
                datos.forEach((element, index) => {
                    element.backgroundColor = colorAleatorio == true ? genColor() : asignarColor(index);
                });
            } else {
                datos.forEach((element, index) => {
                    let c = [];
                    element.data.forEach(() => c.push(genColor()));
                    element.backgroundColor = c;
                    element.borderColor = [];
                });
            }
            break;
        case "pie":
        case "doughnut":
            datos.forEach((element, index) => {
                let c = [];
                element.data.forEach((element, index) => {
                    c.push(colorAleatorio == true ? genColor() : asignarColor(index))
                });
                element.backgroundColor = c;
                element.borderColor = [];
            });
            break;
        case "line":
            datos.forEach((element, index) => {
                element.backgroundColor = [];
                element.borderColor = colorAleatorio == true ? genColor() : asignarColor(index);
                element.fill = false;
                element.showLine = true;
            });
            break;
        case "linePuntos":
            datos.forEach((element, index) => {
                element.backgroundColor = [];
                element.backgroundColor = colorAleatorio == true ? genColor() : asignarColor(index);
                element.borderColor = colorAleatorio == true ? genColor() : asignarColor(index);
                element.fill = false;
                element.showLine = false;
                element.pointRadius = 5;
            });
            tipoGrafico = "line";
            break;
        case "polarArea":
            datos.forEach((element, index) => {
                let c = [];
                element.data.forEach((element, index) => {
                    c.push(colorAleatorio == true ? genColor() : asignarColor(index))
                });
                element.backgroundColor = c;
                element.borderColor = [];
            });
            break;
        default:
            break;
    }

    let ctx = document.querySelector(elemento).getContext('2d');

    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: tipoGrafico,
        data: {
            labels: etiquetas,
            datasets: datos
        },
        options: {
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: titulos['ejex']
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: titulos['ejey']
                    }
                }]
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            title: {
                display: false,
                position: 'top',
                text: titulos['titulo']
            }
        }
    });

}

export function graficarHighcharts(elemento, tipoGrafico, etiquetas, datos, titulos, colorAleatorio) {

    datos.forEach((element, index) => {
        element.color = colorAleatorio == true ? genColor() : asignarColor(index);
    });

    if (tipoGrafico == "pie") {
        datos.forEach((element, index) => {
            let newArray = [];
            element.data.forEach((item, index2) => {
                newArray.push({ name: etiquetas[index2], y: item });
                element.data = newArray;
            })
        })
    }

    myChart = Highcharts.chart(elemento, {
        chart: {
            type: tipoGrafico
        },
        title: {
            text: titulos['titulo']
        },
        subtitle: {
            text: titulos['subtitulo']
        },
        xAxis: {
            categories: etiquetas,
            title: {
                text: titulos['ejex']
            }
        },
        yAxis: {
            title: {
                text: titulos['ejey']
            }
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
            }
        },
        series: datos,
        credits: {
            enabled: true,
            text: 'xperticia.com',
            href: 'http://www.xperticia.com'
        }
    });

}

/* --------------------------------------------------------- */

export function numeroALetras(num, currency) {
    let resultado;

    function Unidades(num) {
        switch (num) {
            case 1:
                return "UN";
            case 2:
                return "DOS";
            case 3:
                return "TRES";
            case 4:
                return "CUATRO";
            case 5:
                return "CINCO";
            case 6:
                return "SEIS";
            case 7:
                return "SIETE";
            case 8:
                return "OCHO";
            case 9:
                return "NUEVE";
        }

        return "";
    } //Unidades()

    function Decenas(num) {
        let decena = Math.floor(num / 10);
        let unidad = num - decena * 10;

        switch (decena) {
            case 1:
                switch (unidad) {
                    case 0:
                        return "DIEZ";
                    case 1:
                        return "ONCE";
                    case 2:
                        return "DOCE";
                    case 3:
                        return "TRECE";
                    case 4:
                        return "CATORCE";
                    case 5:
                        return "QUINCE";
                    default:
                        return "DIECI" + Unidades(unidad);
                }
            case 2:
                switch (unidad) {
                    case 0:
                        return "VEINTE";
                    default:
                        return "VEINTI" + Unidades(unidad);
                }
            case 3:
                return DecenasY("TREINTA", unidad);
            case 4:
                return DecenasY("CUARENTA", unidad);
            case 5:
                return DecenasY("CINCUENTA", unidad);
            case 6:
                return DecenasY("SESENTA", unidad);
            case 7:
                return DecenasY("SETENTA", unidad);
            case 8:
                return DecenasY("OCHENTA", unidad);
            case 9:
                return DecenasY("NOVENTA", unidad);
            case 0:
                return Unidades(unidad);
        }
    } //Unidades()

    function DecenasY(strSin, numUnidades) {
        if (numUnidades > 0) return strSin + " Y " + Unidades(numUnidades);

        return strSin;
    } //DecenasY()

    function Centenas(num) {
        let centenas = Math.floor(num / 100);
        let decenas = num - centenas * 100;

        switch (centenas) {
            case 1:
                if (decenas > 0) return "CIENTO " + Decenas(decenas);
                return "CIEN";
            case 2:
                return "DOSCIENTOS " + Decenas(decenas);
            case 3:
                return "TRESCIENTOS " + Decenas(decenas);
            case 4:
                return "CUATROCIENTOS " + Decenas(decenas);
            case 5:
                return "QUINIENTOS " + Decenas(decenas);
            case 6:
                return "SEISCIENTOS " + Decenas(decenas);
            case 7:
                return "SETECIENTOS " + Decenas(decenas);
            case 8:
                return "OCHOCIENTOS " + Decenas(decenas);
            case 9:
                return "NOVECIENTOS " + Decenas(decenas);
        }

        return Decenas(decenas);
    } //Centenas()

    function Seccion(num, divisor, strSingular, strPlural) {
        let cientos = Math.floor(num / divisor);
        let resto = num - cientos * divisor;

        let letras = "";

        if (cientos > 0)
            if (cientos > 1) letras = Centenas(cientos) + " " + strPlural;
            else letras = strSingular;

        if (resto > 0) letras += "";

        return letras;
    } //Seccion()

    function Miles(num) {
        let divisor = 1000;
        let cientos = Math.floor(num / divisor);
        let resto = num - cientos * divisor;

        let strMiles = Seccion(num, divisor, "UN MIL", "MIL");
        let strCentenas = Centenas(resto);

        if (strMiles == "") return strCentenas;

        return strMiles + " " + strCentenas;
    } //Miles()

    function Millones(num) {
        let divisor = 1000000;
        let cientos = Math.floor(num / divisor);
        let resto = num - cientos * divisor;

        let strMillones = Seccion(num, divisor, "UN MILLON DE", "MILLONES DE");
        let strMiles = Miles(resto);

        if (strMillones == "") return strMiles;

        return strMillones + " " + strMiles;
    } //Millones()

    currency = currency || {};
    let data = {
        numero: num,
        enteros: Math.floor(num),
        centavos: Math.round(num * 100) - Math.floor(num) * 100,
        letrasCentavos: "",
        letrasMonedaPlural: currency.plural || "BOLIVIANOS", //'PESOS', 'Dólares', 'Bolívares', 'etcs'
        letrasMonedaSingular: currency.singular || "BOLIVIANO", //'PESO', 'Dólar', 'Bolivar', 'etc'
        letrasMonedaCentavoPlural: currency.centPlural || "CENTAVOS",
        letrasMonedaCentavoSingular: currency.centSingular || "CENTAVO"
    };

    /*
    if (data.centavos > 0) {
        data.letrasCentavos =
            "CON " +
            (function() {
                if (data.centavos == 1)
                    return (
                        Millones(data.centavos) + " " + data.letrasMonedaCentavoSingular
                    );
                else
                    return (
                        Millones(data.centavos) + " " + data.letrasMonedaCentavoPlural
                    );
            })();
    }
    */

    if (data.centavos > 0) {
        data.letrasCentavos = data.centavos + "/100";
    } else {
        data.letrasCentavos = "00/100";
    }

    if (data.enteros == 0)
        resultado = "CERO " + data.letrasCentavos + " " + data.letrasMonedaPlural;
    if (data.enteros == 1) {
        resultado = Millones(data.enteros) + " " + data.letrasCentavos + " " + data.letrasMonedaSingular;
    } else {
        resultado = Millones(data.enteros) + " " + data.letrasCentavos + " " + data.letrasMonedaPlural;
    }
    return resultado;

}

export function sprintf(format) {
    // Check for format definition
    if (typeof format != 'string') {
        throw "sprintf: The first arguments need to be a valid format string.";
    }

    /**
     * Define the regex to match a formating string
     * The regex consists of the following parts:
     * percent sign to indicate the start
     * (optional) sign specifier
     * (optional) padding specifier
     * (optional) alignment specifier
     * (optional) width specifier
     * (optional) precision specifier
     * type specifier:
     * % - literal percent sign
     * b - binary number
     * c - ASCII character represented by the given value
     * d - signed decimal number
     * f - floating point value
     * o - octal number
     * s - string
     * x - hexadecimal number (lowercase characters)
     * X - hexadecimal number (uppercase characters)
     */
    var r = new RegExp(/%(\+)?([0 ]|'(.))?(-)?([0-9]+)?(\.([0-9]+))?([%bcdfosxX])/g);

    /**
     * Each format string is splitted into the following parts:
     * 0: Full format string
     * 1: sign specifier (+)
     * 2: padding specifier (0/<space>/'<any char>)
     * 3: if the padding character starts with a ' this will be the real
     * padding character
     * 4: alignment specifier
     * 5: width specifier
     * 6: precision specifier including the dot
     * 7: precision specifier without the dot
     * 8: type specifier
     */
    var parts = [];
    var paramIndex = 1;
    var part;
    while (part = r.exec(format)) {
        // Check if an input value has been provided, for the current
        // format string (no argument needed for %%)
        if ((paramIndex >= arguments.length) && (part[8] != '%')) {
            throw "sprintf: At least one argument was missing.";
        }

        parts[parts.length] = {
            /* beginning of the part in the string */
            begin: part.index,
            /* end of the part in the string */
            end: part.index + part[0].length,
            /* force sign */
            sign: (part[1] == '+'),
            /* is the given data negative */
            negative: (parseFloat(arguments[paramIndex]) < 0) ? true : false,
            /* padding character (default: <space>) */
            padding: (part[2] == undefined) ?
                (' ') /* default */ :
                ((part[2].substring(0, 1) == "'") ?
                    (part[3]) /* use special char */ :
                    (part[2]) /* use normal <space> or zero */
                ),
            /* should the output be aligned left?*/
            alignLeft: (part[4] == '-'),
            /* width specifier (number or false) */
            width: (part[5] != undefined) ? part[5] : false,
            /* precision specifier (number or false) */
            precision: (part[7] != undefined) ? part[7] : false,
            /* type specifier */
            type: part[8],
            /* the given data associated with this part converted to a string */
            data: (part[8] != '%') ? String(arguments[paramIndex++]) : false
        };
    }

    var newString = "";
    var start = 0;
    // Generate our new formated string
    for (var i = 0; i < parts.length; ++i) {
        // Add first unformated string part
        newString += format.substring(start, parts[i].begin);

        // Mark the new string start
        start = parts[i].end;

        // Create the appropriate preformat substitution
        // This substitution is only the correct type conversion. All the
        // different options and flags haven't been applied to it at this
        // point
        var preSubstitution = "";
        switch (parts[i].type) {
            case '%':
                preSubstitution = "%";
                break;
            case 'b':
                preSubstitution = Math.abs(parseInt(parts[i].data)).toString(2);
                break;
            case 'c':
                preSubstitution = String.fromCharCode(Math.abs(parseInt(parts[i].data)));
                break;
            case 'd':
                preSubstitution = String(Math.abs(parseInt(parts[i].data)));
                break;
            case 'f':
                preSubstitution = (parts[i].precision == false) ?
                    (String((Math.abs(parseFloat(parts[i].data))))) :
                    (Math.abs(parseFloat(parts[i].data)).toFixed(parts[i].precision));
                break;
            case 'o':
                preSubstitution = Math.abs(parseInt(parts[i].data)).toString(8);
                break;
            case 's':
                preSubstitution = parts[i].data.substring(0, parts[i].precision ? parts[i].precision : parts[i].data.length); /* Cut if precision is defined */
                break;
            case 'x':
                preSubstitution = Math.abs(parseInt(parts[i].data)).toString(16).toLowerCase();
                break;
            case 'X':
                preSubstitution = Math.abs(parseInt(parts[i].data)).toString(16).toUpperCase();
                break;
            default:
                throw 'sprintf: Unknown type "' + parts[i].type + '" detected. This should never happen. Maybe the regex is wrong.';
        }

        // The % character is a special type and does not need further processing
        if (parts[i].type == "%") {
            newString += preSubstitution;
            continue;
        }

        // Modify the preSubstitution by taking sign, padding and width
        // into account

        // Pad the string based on the given width
        if (parts[i].width != false) {
            // Padding needed?
            if (parts[i].width > preSubstitution.length) {
                var origLength = preSubstitution.length;
                for (var j = 0; j < parts[i].width - origLength; ++j) {
                    preSubstitution = (parts[i].alignLeft == true) ?
                        (preSubstitution + parts[i].padding) :
                        (parts[i].padding + preSubstitution);
                }
            }
        }

        // Add a sign symbol if neccessary or enforced, but only if we are
        // not handling a string
        if (parts[i].type == 'b' ||
            parts[i].type == 'd' ||
            parts[i].type == 'o' ||
            parts[i].type == 'f' ||
            parts[i].type == 'x' ||
            parts[i].type == 'X') {
            if (parts[i].negative == true) {
                preSubstitution = "-" + preSubstitution;
            } else if (parts[i].sign == true) {
                preSubstitution = "+" + preSubstitution;
            }
        }

        // Add the substitution to the new string
        newString += preSubstitution;
    }

    // Add the last part of the given format string, which may still be there
    newString += format.substring(start, format.length);

    return newString;
};

export function loadScript(src, funcion) {
    let script = document.createElement('script');
    script.src = src;
    script.async = false;
    document.body.append(script);

    script.onload = funcion;
}

export function loadCss(src) {
    var cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = src;
    var head = document.getElementsByTagName('head')[0];
    head.parentNode.insertBefore(cssLink, head);
};

export function cargarSCEDITOR() {
    if (typeof sceditor !== 'undefined') {
        let optionsEditor = {
            format: 'xhtml',
            icons: 'monocons',
            style: '../js/SCEditor/minified/themes/square.min.css',
            toolbar: 'bold,italic,underline,strike,subscript,superscript|left,center,right,justify|font,size,color,removeformat|cut,copy,paste,pastetext|bulletlist,orderedlist|table|code,quote|horizontalrule,image,youtube,email,link,unlink|maximize,source',
            emoticonsEnabled: false
        }
        var textarea;
        document.querySelectorAll('[rel="sceditor"]').forEach(item => {
            textarea = document.getElementById(item.id);
            sceditor.create(textarea, optionsEditor);
        })
    }
}

export function formatearNumeroConDecimales(event, decimales = 3) {
    let importe = event.target.value.replace(/\./g, "").replace(/\,/g, ".");
    if (importe == null || !isFinite(importe)) {
        importe = 0;
    }
    event.target.value = number_format(importe, decimales, ",", ".");
}

export function focusNextElement(reverse, activeElem) {
    /*check if an element is defined or use activeElement*/
    activeElem = activeElem instanceof HTMLElement ? activeElem : document.activeElement;

    let queryString = [
            'a:not([disabled]):not([tabindex="-1"])',
            'button:not([disabled]):not([tabindex="-1"])',
            'input:not([disabled]):not([tabindex="-1"])',
            'select:not([disabled]):not([tabindex="-1"])',
            '[tabindex]:not([disabled]):not([tabindex="-1"])'
            /* add custom queries here */
        ].join(','),
        queryResult = Array.prototype.filter.call(document.querySelectorAll(queryString), elem => {
            /*check for visibility while always include the current activeElement*/
            return elem.offsetWidth > 0 || elem.offsetHeight > 0 || elem === activeElem;
        }),
        indexedList = queryResult.slice().filter(elem => {
            /* filter out all indexes not greater than 0 */
            return elem.tabIndex == 0 || elem.tabIndex == -1 ? false : true;
        }).sort((a, b) => {
            /* sort the array by index from smallest to largest */
            return a.tabIndex != 0 && b.tabIndex != 0 ?
                (a.tabIndex < b.tabIndex ? -1 : b.tabIndex < a.tabIndex ? 1 : 0) :
                a.tabIndex != 0 ? -1 : b.tabIndex != 0 ? 1 : 0;
        }),
        focusable = [].concat(indexedList, queryResult.filter(elem => {
            /* filter out all indexes above 0 */
            return elem.tabIndex == 0 || elem.tabIndex == -1 ? true : false;
        }));

    /* if reverse is true return the previous focusable element
       if reverse is false return the next focusable element */
    return reverse ? (focusable[focusable.indexOf(activeElem) - 1] || focusable[focusable.length - 1]) : (focusable[focusable.indexOf(activeElem) + 1] || focusable[0]);
}

export function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition((position) => {
            if (document.querySelector('#latitud')) {
                document.querySelector('#latitud').value = position.coords.latitude;
            }
            if (document.querySelector('#longitud')) {
                document.querySelector('#longitud').value = position.coords.longitude;
            }
        }, (error) => {
            console.log('ERROR(' + error.code + '): ' + error.message);
        }, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 });
    } else {
        console.log("Geolocation no es soportado por este navegador.");
    }

}

/* --------------------------------------------------------- */
export function initializarMapaUbicacion(latitud, longitud) {
    let polygonArrayPuntos = [];
    let polylineArrayPuntos = [];

    let mymapa = L.map("mapa").setView([latitud.value, longitud.value], 16);
    let geocoder = L.Control.Geocoder.nominatim();
    let control = L.Control.geocoder({ placeholder: "buscar lugar", geocoder: geocoder }).addTo(mymapa);

    L.tileLayer(
        "https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw", {
            maxZoom: 18,
            dragging: true,
            attribution: '&copy; <a href="http://www.xperticia.com" targe="_blank" rel="noopener noreferrer">xperticia.com</a>',
            id: "mapbox.streets"
        }
    ).addTo(mymapa);

    mymapa.zoomControl.setPosition('bottomright');

    let actualizarMapa = setTimeout(function() { mymapa.invalidateSize(); }, 400);
    //clearTimeout(actualizarMapa);

    let marker = L.marker([latitud.value, longitud.value])
        .addTo(mymapa);

    function onMapClick(e) {
        marker.setLatLng(e.latlng);
        latitud.value = e.latlng["lat"];
        longitud.value = e.latlng["lng"];
    }
    mymapa.addEventListener('click', onMapClick);

    function crearPolilineaPuntos(lista) {
        borrarPolilineaPuntos();
        var posiciones = [];
        lista.each(function(idx, el) {
            var parametros = $(el).val();
            var valores = parametros.split(",");
            posiciones.push([valores[0], valores[1]]);
        });
        var polyline = L.polyline(posiciones, { color: "red" }).addTo(mymapa);
        polylineArrayPuntos[polylineArrayPuntos.length] = polyline;
    }

    function borrarPolilineaPuntos() {
        for (i = 0; i < polylineArrayPuntos.length; i++) {
            mymapa.removeLayer(polylineArrayPuntos[i]);
        }
        polylineArrayPuntos = [];
    }

    function crearPoligonoPuntos(lista) {
        borrarPoligonosPuntos();
        var posiciones = [];
        lista.each(function(idx, el) {
            var parametros = $(el).val();
            var valores = parametros.split(",");
            posiciones.push([valores[0], valores[1]]);
        });
        var polygon = L.polygon(posiciones, { color: "red" }).addTo(mymapa);
        polygonArrayPuntos[polygonArrayPuntos.length] = polygon;
    }

    function borrarPoligonosPuntos() {
        for (i = 0; i < polygonArrayPuntos.length; i++) {
            mymapa.removeLayer(polygonArrayPuntos[i]);
        }
        polygonArrayPuntos = [];
    }

    function actualizarCoordenadas() {
        mymapa.setView([latitud.value, longitud.value], 16);
        marker.setLatLng(L.latLng(latitud.value, longitud.value));
        mymapa.invalidateSize();
    }
    var recalcularImporte = (e) => {
        if (e.keyCode === 13) {
            e.currentTarget.dispatchEvent(new Event('change'));
            e.target.focus();
            return false;
        }
    };

    latitud.addEventListener('change', actualizarCoordenadas, { once: false });
    latitud.addEventListener('keyup', recalcularImporte, { once: false });
    longitud.addEventListener('change', actualizarCoordenadas, { once: false });
    longitud.addEventListener('keyup', recalcularImporte, { once: false });

    if ($("#vPuntos").length) {
        if ($("#vPuntos option").length) {
            if (getUrlVars()["t"] == "recorrido") {
                crearPolilineaPuntos($("#vPuntos option"));
            } else {
                crearPoligonoPuntos($("#vPuntos option"));
            }
        }
        $("#vPuntos").change(function() {
            if ($("#vPuntos option:selected").val() != "") {
                var valores = $("#vPuntos option:selected")
                    .val()
                    .split(",");
                marker.setLatLng([valores[0], valores[1]]);
                mymapa.panTo([valores[0], valores[1]]);
                $("#vLatitud").val(valores[0]);
                $("#vLongitud").val(valores[1]);
            }
        });
    }

    if ($("input[type='button']").length) {
        $("input[type='button']").click(function() {
            switch ($(this).attr("name")) {
                case "agregarPosicion":
                    $("#vPuntos").append(
                        "<option value='" + $("#vLatitud").val() + "," + $("#vLongitud").val() + "'>" + $("#vLatitud").val() + "," + $("#vLongitud").val() + "</option>"
                    );
                    if (getUrlVars()["t"] == "recorrido") {
                        crearPolilineaPuntos($("#vPuntos option"));
                    } else {
                        crearPoligonoPuntos($("#vPuntos option"));
                    }
                    break;
                case "eliminarPosicion":
                    if (!$("#vPuntos option:selected").val()) {
                        window.alert("Usted no ha seleccionado ningun elemento \no no posee elementos en la lista.");
                    } else {
                        if (confirm('Esta seguro que desea eliminar la posicion seleccionada: \n"' + $("#vPuntos option:selected").val() + '" ')) {
                            $("#vPuntos option:selected").remove();
                            if (getUrlVars()["t"] == "recorrido") {
                                crearPolilineaPuntos($("#vPuntos option"));
                            } else {
                                crearPoligonoPuntos($("#vPuntos option"));
                            }
                        }
                    }
                    break;
                case "subirPosicion":
                    if (!$("#vPuntos option:selected").val()) {
                        window.alert("Usted no ha seleccionado ningun elemento para mover \no no posee elementos en la lista.");
                    } else {
                        $("#vPuntos option:selected").each(function() {
                            $(this).insertBefore($(this).prev());
                        });
                        if (getUrlVars()["t"] == "recorrido") {
                            crearPolilineaPuntos($("#vPuntos option"));
                        } else {
                            crearPoligonoPuntos($("#vPuntos option"));
                        }
                    }
                    break;
                case "bajarPosicion":
                    if (!$("#vPuntos option:selected").val()) {
                        window.alert("Usted no ha seleccionado ningun elemento para mover \no no posee elementos en la lista.");
                    } else {
                        $("#vPuntos option:selected").each(function() {
                            $(this).insertAfter($(this).next());
                        });
                        if (getUrlVars()["t"] == "recorrido") {
                            crearPolilineaPuntos($("#vPuntos option"));
                        } else {
                            crearPoligonoPuntos($("#vPuntos option"));
                        }
                    }
                    break;
                case "seleccionarPosiciones":
                    $("#vPuntos option").each(function(idx) {
                        $(this).attr("selected", true);
                    });
                    break;
                case "dibujarPosiciones":
                    if (getUrlVars()["t"] == "recorrido") {
                        crearPolilineaPuntos($("#vPuntos option"));
                    } else {
                        crearPoligonoPuntos($("#vPuntos option"));
                    }
                    break;
                case "limpiarMapa":
                    /*
                    borrarMarcadores();
                    borrarPoligonos();
                    borrarMarcadoresFichas();
                    borrarMarcadoresElementos();
                    */
                    borrarPoligonosPuntos();
                    borrarPolilineaPuntos();

                    marker.setLatLng([$("#vLatitud").val(), $("#vLongitud").val()]);
                    mymapa.panTo([$("#vLatitud").val(), $("#vLongitud").val()]);
                    break;
                case "aceptar":
                    if ($("#vPuntos").length) {
                        var vPuntos = "";
                        $("option", $("#vPuntos")).each(function(i) {
                            var valores = $(this).attr("value").split(",");
                            vPuntos += valores[0] + "," + valores[1] + "|";
                        });
                        returnVal = vPuntos;
                    } else {
                        returnVal = $("#vLatitud").val() + "," + $("#vLongitud").val();
                    }
                    window.parent.hidePopWin(true);
                    break;
                default:
                    break;
            }
        });
    }

}

export function loadMapsJSAPI() {
    const googleMapsAPIKey = 'AIzaSyBhEkplWNuoX9zNY6M3Tq5FvZ76wJInf9A';
    const googleMapsAPIURI = `https://maps.googleapis.com/maps/api/js?key=${googleMapsAPIKey}&callback=runApp`;

    const script = document.createElement('script');
    script.src = googleMapsAPIURI;
    script.defer = true;
    script.async = true;

    window.runApp = runApp;

    document.head.appendChild(script);
}

export function initializarMapaUbicacionGoogle(latitud, longitud) {
    const urlMarker = '../images/marker.png'
    if (!latitud) latitud = document.querySelector('input[type="text"]#vLatitud');
    if (!longitud) longitud = document.querySelector('input[type="text"]#vLongitud');

    var infowindow;
    var markerArray = [];
    var markerDelitosArray = [];
    var markerElementosArray = [];
    var markerEventosArray = [];
    var markerExcepcionalArray = [];
    var polygonArray = [];
    var polylineArray = [];
    var listaColores = ['#BCE02E', '#E0642E', '#E0D62E', '#2E97E0', '#B02EE0', '#E02E75', '#5CE02E', '#E0B02E', '#4074C8', '#C00000', '#FF0000', '#FFC000', '#FFFF00', '#92D050', '#00B050', '#00B0F0', '#0070C0', '#002060', '#7030A0', '#984807', '#E46C0A', '#FAC090', '#FCD5B5', '#FDEADA', '#F79646', '#4BACC6', '#DBEEF4', '#B7DEE8', '#93CDDD', '#31859C', '#215968', '#403152', '#604A7B', '#B3A2C7', '#CCC1DA', '#E6E0EC', '#8064A2', '#9BBB59', '#EBF1DE', '#D7E4BD', '#C3D69B', '#77933C', '#4F6228', '#632523', '#953735', '#D99694', '#E6B9B8', '#F2DCDB', '#C0504D', '#4F81BD', '#DCE6F2', '#B9CDE5', '#95B3D7', '#376092', '#254061', '#10253F', '#17375E', '#558ED5', '#8EB4E3', '#C6D9F1', '#1F497D', '#EEECE1', '#DDD9C3', '#C4BD97', '#948A54', '#4A452A'];
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();

    myLatlng = new google.maps.LatLng(latitud.value, longitud.value);

    var myOptions = {
        zoom: 16,
        center: myLatlng,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('mapa'), myOptions);
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);

    marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: urlMarker,
        draggable: true,
        title: "Aquí estoy"
    });

    infowindow = new google.maps.InfoWindow();

    google.maps.event.addListener(map, 'click', function(event) {
        marker.setMap(null);
        marker = new google.maps.Marker({
            map: map,
            icon: urlMarker,
            draggable: true,
            title: "Aquí estoy"
        });
        marker.setPosition(event.latLng);
        latitud.value = event.latLng.lat()
        longitud.value = event.latLng.lng()
        $("#btObtenerDireccion").trigger('click');

        google.maps.event.addListener(marker, 'click', function(event) {
            if (marker.getAnimation() != null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        });

        google.maps.event.addListener(marker, 'dragend', function(event) {
            $("#latitud").val(event.latLng.lat());
            $("#longitud").val(event.latLng.lng());
        });

    });

    google.maps.event.addListener(marker, 'dragend', function(event) {
        $("#latitud").val(event.latLng.lat());
        $("#longitud").val(event.latLng.lng());
    });

    // detecta la ubicacion del usuario actual
    if ($("#btn-detectar-ubicacion").length) {
        console.log('#btn-detectar-ubicacion');
        $("#btn-detectar-ubicacion").click(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    latitud.value = position.coords.latitude
                    longitud.value = position.coords.longitude

                    marker.setMap(null);
                    marker = new google.maps.Marker({
                        map: map,
                        icon: urlMarker,
                        draggable: true,
                        title: "Aquí estoy"
                    });
                    myLatlng = new google.maps.LatLng(latitud.value, longitud.value);
                    marker.setPosition(myLatlng);
                    map.panTo(myLatlng);

                }, (error) => {
                    console.log('ERROR(' + error.code + '): ' + error.message)
                }, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 });
            } else {
                console.log("Geolocation no es soportado por este navegador.")
            }

        })
    }

    function crearMarcador(Lat, Lng, titulo, tipo) {
        var latlng = new google.maps.LatLng(Lat, Lng);
        var aMarker = new google.maps.Marker({
            position: latlng,
            map: map,
            draggable: true,
            title: tipo + ": " + titulo
        });
        switch (tipo) {
            case "Zona":
                aMarker.setIcon("images/maps/zonas.png");
                break;
            case "Distrito":
                aMarker.setIcon("images/maps/distritos.png");
                break;
            case "Barrio":
                aMarker.setIcon("images/maps/barrios.png");
                break;
            case "Instituciones":
                aMarker.setIcon("images/maps/institucion.png");
                break;
        }
        google.maps.event.addListener(aMarker, 'dragend', function(event) {
            $("#latitud").val(event.latLng.lat());
            $("#longitud").val(event.latLng.lng());
        });
        markerArray[markerArray.length] = aMarker;
    }

    function borrarMarcadores() {
        for (i = 0; i < markerArray.length; i++) {
            markerArray[i].setMap(null);
        }
        markerArray = [];
        infowindow.setMap(null);
    }

    //	elementos
    function crearMarcadorElementos(elemento) {
        //	Lat,Lng,nombre,tipo,categoria,descripcion
        var latlng = new google.maps.LatLng(elemento.latitud, elemento.longitud);
        var aMarker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: "[ " + elemento.tipo + " ] " + elemento.nombre
        });
        if ((elemento.rating == "Excepcional") && (elemento.icono != "")) {
            aMarker.setIcon("images/iconos/" + elemento.icono);
        } else {
            if (elemento.categoria != "") {
                aMarker.setIcon(obtenerIconoElemento(elemento.categoria, elemento.tipo));
            }
        }
        markerElementosArray[markerElementosArray.length] = aMarker;

        google.maps.event.addListener(aMarker, 'click', function(event) {
            var contentString = '';
            contentString += '<div id="contenidoInfo" style="color: #333333;">';
            contentString += '	<h3 style="margin-bottom: 0;">' + elemento.nombre + '</h3>';
            contentString += '	<p style="margin-top: 0;color: #333333;">[ ' + elemento.categoria + ' ] : ' + elemento.tipo + '</p>';
            contentString += '	<p style="margin-top: 10px;color: #333333;">';
            contentString += '		<strong>Domicilio:</strong> ' + elemento.domicilio + '<br />';
            contentString += '		<strong>Teléfono:</strong> ' + elemento.telefonos + '<br />';
            contentString += '		<strong>Sitio Web:</strong> <a href="' + elemento.url + '" target="_blank">' + elemento.url + '</a><br />';
            contentString += '		<strong>Correo electrónico:</strong> <a href="mailto:' + elemento.email + '">' + elemento.email + '</a>';
            contentString += '	</p>';
            contentString += '	<p><a class="btn btn-primary" name="webmovil" data-rating="' + elemento.rating + '" title="[ ' + elemento.tipo + ' ] ' + elemento.nombre + '" data-id="' + elemento.id + '" />Web Móvil</a></p>';
            contentString += '</div>';

            infowindow.setContent(contentString);
            infowindow.setPosition(event.latLng);
            infowindow.open(map);

            if ($("a[name='webmovil']").length) { //	marco la publicidad seleccionada
                $("a[name='webmovil']").click(function() {

                    $.get("html/buscarDatos.php?t=ClickWebmovil&id=" + $(this).attr('data-id'));

                    $(".elemento").attr("title", $(this).attr('title'));
                    $(".elemento").colorbox({ iframe: true, href: "elemento.php?id=" + $(this).attr('data-id') + "&lat=" + $("#latitud").val() + "&lng=" + $("#longitud").val(), open: true, rel: "elemento" + $(this).attr('data-id'), width: "90%", height: "90%" });
                })
            };

            $.get("html/buscarDatos.php?t=ClickIcono&id=" + elemento.id + "&m=elemento");
        });
    }

    function borrarMarcadoresElementos() {
        for (i = 0; i < markerElementosArray.length; i++) {
            markerElementosArray[i].setMap(null);
        }
        markerElementosArray = [];
        infowindow.setMap(null);
        directionsDisplay.setMap(null);
    }

    //	eventos
    function crearMarcadorEventos(elemento) {
        //	Lat,Lng,nombre,tipo,categoria,descripcion
        var latlng = new google.maps.LatLng(elemento.latitud, elemento.longitud);
        var aMarker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: "[ " + $("<div/>").html(elemento.tipo).text() + " ] " + $("<div/>").html(elemento.nombre).text()
        });
        if ((elemento.rating == "Recomendado") || (elemento.rating == "Muy Recomendado") || (elemento.rating == "Muy Altamente Recomendado") || (elemento.rating == "Excepcional")) {
            if ((elemento.rating == "Excepcional") && (elemento.icono != "")) {
                aMarker.setIcon("images/iconos/" + elemento.icono);
            } else {
                if (elemento.icono != "") {
                    aMarker.setIcon("images/iconos/" + elemento.icono);
                } else {
                    aMarker.setIcon("images/maps/eventosdestacados.png");
                }
            }

            google.maps.event.addListener(aMarker, 'click', function(event) {
                var contentString = '';
                contentString += '<div id="contenidoInfo" style="color: #333333;">';
                contentString += '	<h3 style="margin-bottom: 0;">' + elemento.nombre + '</h3>';
                contentString += '	<p style="margin-top: 0;color: #333333;">[ ' + elemento.categoria + ' ] : ' + elemento.tipo + '</p>';
                contentString += '	<p style="margin-top: 10px;color: #333333;">';
                contentString += '		<strong>Domicilio:</strong> ' + elemento.domicilio + '<br />';
                contentString += '		<strong>Teléfono:</strong> ' + elemento.telefonos + '<br />';
                contentString += '		<strong>Sitio Web:</strong> <a href="' + elemento.url + '" target="_blank">' + elemento.url + '</a><br />';
                contentString += '		<strong>Correo electrónico:</strong> <a href="mailto:' + elemento.email + '">' + elemento.email + '</a>';
                contentString += '	</p>';
                contentString += '	<p><a class="btn btn-primary" name="webmovil" data-rating="' + elemento.rating + '" title="[ ' + elemento.tipo + ' ] ' + elemento.nombre + '" data-id="' + elemento.id + '" />Web Móvil</a></p>';
                contentString += '</div>';

                infowindow.setContent(contentString);
                infowindow.setPosition(event.latLng);
                infowindow.open(map);

                if ($("a[name='webmovil']").length) { //	marco la publicidad seleccionada
                    $("a[name='webmovil']").click(function() {

                        $.get("html/buscarDatos.php?t=ClickWebmovil&id=" + $(this).attr('data-id'));

                        $(".elemento").attr("title", $(this).attr('title'));
                        $(".elemento").colorbox({ iframe: true, href: "elemento.php?id=" + $(this).attr('data-id') + "&lat=" + $("#latitud").val() + "&lng=" + $("#longitud").val(), open: true, rel: "elemento" + $(this).attr('data-id'), width: "90%", height: "90%" });
                    })
                };

                $.get("html/buscarDatos.php?t=ClickIcono&id=" + elemento.id + "&m=elemento");
            });
        } else {
            aMarker.setIcon("images/maps/agendaeventos.png");

            google.maps.event.addListener(aMarker, 'click', function(event) {
                var contentString = '';
                contentString += '<div id="contenidoInfo" style="color: #333333;">';
                contentString += '	<h3 style="margin-bottom: 0;">' + elemento.nombre + '</h3>';
                contentString += '	<p style="margin-top: 0;color: #333333;">[ ' + elemento.tipo + ' ] : ' + elemento.rating + '</p>';
                contentString += '	<p style="margin-top: 10px;color: #333333;">';
                contentString += '		<strong>Fechas:</strong> ' + elemento.domicilio + ' ' + elemento.telefonos + ' a ' + elemento.url + ' ' + elemento.email + '<br />';
                contentString += '		<strong>Descripción:</strong><br /> ' + elemento.categoria + '<br />';
                contentString += '	</p>';
                contentString += '	<p><a class="btn btn-primary" name="webmovil" title="Web Móvil: ' + elemento.rating + '" data-id="' + elemento.icono + '" />Web Móvil</a></p>';
                contentString += '</div>';

                infowindow.setContent(contentString);
                infowindow.setPosition(event.latLng);
                infowindow.open(map);

                if ($("a[name='webmovil']").length) { //	marco la publicidad seleccionada
                    $("a[name='webmovil']").click(function() {

                        $.get("html/buscarDatos.php?t=ClickWebmovil&id=" + $(this).attr('data-id'));

                        $(".elemento").attr("title", $(this).attr('title'));
                        $(".elemento").colorbox({ iframe: true, href: "elemento.php?id=" + $(this).attr('data-id') + "&lat=" + $("#latitud").val() + "&lng=" + $("#longitud").val(), open: true, rel: "elemento" + $(this).attr('data-id'), width: "90%", height: "90%" });
                    })
                };

                $.get("html/buscarDatos.php?t=ClickIcono&id=" + elemento.id + "&m=evento");
            });
        }
        markerEventosArray[markerEventosArray.length] = aMarker;
    }

    function borrarMarcadoresEventos() {
        for (i = 0; i < markerEventosArray.length; i++) {
            markerEventosArray[i].setMap(null);
        }
        markerEventosArray = [];
        infowindow.setMap(null);
        directionsDisplay.setMap(null);
    }

    function mostrarMarcadoresEventos() {
        for (i = 0; i < markerEventosArray.length; i++) {
            markerEventosArray[i].setVisible(true);
        }
    }

    function ocultarMarcadoresEventos() {
        for (i = 0; i < markerEventosArray.length; i++) {
            markerEventosArray[i].setVisible(false);
        }
    }

    //	elementos Excepcional
    function crearMarcadorExcepcional(elemento) {
        //	Lat,Lng,nombre,tipo,categoria,descripcion
        var latlng = new google.maps.LatLng(elemento.latitud, elemento.longitud);
        var aMarker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: "[ " + elemento.tipo + " ] " + elemento.nombre
        });
        if ((elemento.rating == "Excepcional") && (elemento.icono != "")) {
            aMarker.setIcon("images/iconos/" + elemento.icono);
        } else {
            aMarker.setIcon(obtenerIconoElemento(elemento.categoria, elemento.tipo));
        }
        markerExcepcionalArray[markerExcepcionalArray.length] = aMarker;

        google.maps.event.addListener(aMarker, 'click', function(event) {
            var contentString = '';
            contentString += '<div id="contenidoInfo" style="color: #333333;">';
            contentString += '	<h3 style="margin-bottom: 0;">' + elemento.nombre + '</h3>';
            contentString += '	<p style="margin-top: 0;color: #333333;">[ ' + elemento.categoria + ' ] : ' + elemento.tipo + '</p>';
            contentString += '	<p style="margin-top: 10px;color: #333333;">';
            contentString += '		<strong>Domicilio:</strong> ' + elemento.domicilio + '<br />';
            contentString += '		<strong>Teléfono:</strong> ' + elemento.telefonos + '<br />';
            contentString += '		<strong>Sitio Web:</strong> <a href="' + elemento.url + '" target="_blank">' + elemento.url + '</a><br />';
            contentString += '		<strong>Correo electrónico:</strong> <a href="mailto:' + elemento.email + '">' + elemento.email + '</a>';
            contentString += '	</p>';
            contentString += '	<p><a class="btn btn-primary" name="webmovil" data-rating="' + elemento.rating + '" title="[ ' + elemento.tipo + ' ] ' + elemento.nombre + '" data-id="' + elemento.id + '" />Web Móvil</a></p>';
            contentString += '</div>';

            infowindow.setContent(contentString);
            infowindow.setPosition(event.latLng);
            infowindow.open(map);

            if ($("a[name='webmovil']").length) { //	marco la publicidad seleccionada
                $("a[name='webmovil']").click(function() {

                    $.get("html/buscarDatos.php?t=ClickWebmovil&id=" + $(this).attr('data-id'));

                    $(".elemento").attr("title", $(this).attr('title'));
                    $(".elemento").colorbox({ iframe: true, href: "elemento.php?id=" + $(this).attr('data-id') + "&lat=" + $("#latitud").val() + "&lng=" + $("#longitud").val(), open: true, rel: "elemento" + $(this).attr('data-id'), width: "90%", height: "90%" });
                })
            };

            $.get("html/buscarDatos.php?t=ClickIcono&id=" + elemento.id + "&m=elemento");
        });
    }

    function borrarMarcadoresExcepcional() {
        for (i = 0; i < markerExcepcionalArray.length; i++) {
            markerExcepcionalArray[i].setMap(null);
        }
        markerExcepcionalArray = [];
        infowindow.setMap(null);
        directionsDisplay.setMap(null);
    }

    function mostrarMarcadoresExcepcional() {
        for (i = 0; i < markerExcepcionalArray.length; i++) {
            markerExcepcionalArray[i].setVisible(true);
        }
    }

    function ocultarMarcadoresExcepcional() {
        for (i = 0; i < markerExcepcionalArray.length; i++) {
            markerExcepcionalArray[i].setVisible(false);
        }
    }

    //	poligonos
    function crearPoligono(posiciones, tipo) {
        if (tipo == "Barrios") {
            var aPolygon = new google.maps.Polygon({
                map: map,
                paths: posiciones,
                strokeColor: "#0000ff",
                strokeOpacity: 0.4,
                strokeWeight: 2,
                fillColor: crearColor(),
                fillOpacity: 0.4
            });
        } else {
            var aPolygon = new google.maps.Polygon({
                map: map,
                paths: posiciones,
                strokeColor: "#ff0000",
                strokeOpacity: 0.4,
                strokeWeight: 2,
                fillColor: listaColores[polygonArray.length],
                fillOpacity: 0.2
            });
        }
        polygonArray[polygonArray.length] = aPolygon;
    }

    function borrarPoligonos() {
        for (i = 0; i < polygonArray.length; i++) {
            polygonArray[i].setMap(null);
        }
        polygonArray = [];
        infowindow.setMap(null);
    }

    function calcularRuta(start, end, mode) {
        directionsDisplay.setMap(null);
        directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();
        directionsDisplay.setMap(map);

        var request = {
            origin: start,
            destination: end,
            unitSystem: google.maps.UnitSystem.METRIC,
            travelMode: google.maps.TravelMode[mode],
            provideRouteAlternatives: true
        };

        directionsService.route(request, function(result, status) {
            $("#accordionDirecciones").empty();
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
                directionsDisplay.setPanel($("#accordionDirecciones").get(0));
            } else {
                $("#accordionDirecciones").append("<p style='text-align: center;color: #ff0000;'>No existen rutas entre ambos puntos en el modo de recorrido: '" + $("button[name='modorecorrido']").filter(".active").attr('title') + "' (" + status + ")</p>");
            }
        });
    }

    //	muestro el recorrido al elemento seleccionado
    if ($("button[name='modorecorrido']").length) {
        $("button[name='modorecorrido']").click(function() {
            $("#divModoSeleccionado").html($(this).attr("title"));

            $("#divDirecciones").show();
            if (!$("#collapseDirecciones").hasClass('in')) {
                $("#divDirecciones").find('div:first').find('a.accordion-toggle').trigger('click');
            }
            $("#accordionDirecciones").empty();
            $("#accordionDirecciones").append("<p style='text-align: center;'><img src='images/preloader.gif' /> obteniendo datos ...</p>");
            //$(window).resize();

            var origen = new google.maps.LatLng($('#latitud').val(), $('#longitud').val());
            var destino = new google.maps.LatLng($('#latitudDestino').val(), $('#longitudDestino').val());

            calcularRuta(origen, destino, $(this).attr("data-mode"));
        })
    }

    //	eventos
    function obtenerEventos() {
        $("#accordionEventos").empty();
        $("#accordionEventos").append("<p style='text-align: center;'><img src='images/preloader.gif' /> cargando datos ...</p>");
        var parametros = "";
        pag = 1;
        cantpag = 1;
        parametros = parametros + "&c=*";
        parametros = parametros + "&pais=" + $('#codigo_pais').val();
        parametros = parametros + "&prov=" + $('#provincia').val();
        parametros = parametros + "&depa=" + $('#departamento').val();
        parametros = parametros + "&ciud=" + $('#ciudad').val();
        parametros = parametros + "&tipo=eventos";

        $.getJSON("html/buscarDatos.php?t=elementosBuscar" + parametros, function(data) {
            borrarMarcadoresEventos();
            $("#accordionEventos").empty();
            if (data.length) {
                $("#accordionEventos").append("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='eventos[]' value='**' checked='checked' /> Todos los eventos</label>");
                $.each(data, function(key, elemento) {
                    if ((elemento.rating == "Recomendado") || (elemento.rating == "Muy Recomendado") || (elemento.rating == "Muy Altamente Recomendado") || (elemento.rating == "Excepcional")) {
                        crearMarcadorEventos(elemento);
                        var Item = $("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='eventos[]' value='" + elemento.id + "' data-tipo='elemento' checked='checked' />  <a href='javascript:void(0);' data-id='" + elemento.id + "' data-tipo='elemento' data-lat='" + elemento.latitud + "' data-lng='" + elemento.longitud + "' title='Mostrar como llegar: " + elemento.nombre + "' name='recorrido[]'><i class='icon-map-marker icon-white'></i></a> <a href='javascript:void(0);' data-id='" + elemento.id + "' title='WebMóvil: " + elemento.nombre + "' name='webmovil[]' data-rating='" + elemento.rating + "'><i class='icon-edit icon-white'></i> </a> " + elemento.nombre + "</label>");
                    } else {
                        //crearMarcadorEventos2(elemento);
                        var Item = $("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='eventos[]' value='" + elemento.id + "' data-tipo='evento' checked='checked' />  <a href='javascript:void(0);' data-id='" + elemento.id + "' data-tipo='evento' data-lat='" + elemento.latitud + "' data-lng='" + elemento.longitud + "' title='Mostrar como llegar: " + elemento.nombre + "' name='recorrido[]'><i class='icon-map-marker icon-white'></i></a> [" + elemento.domicilio + " " + elemento.telefonos + "] " + elemento.nombre + "</label>");
                    }
                    $("#accordionEventos").append(Item);
                });

                if ($("input[name='eventos[]']").length) { //	marco el registro seleccionado
                    $("input[name='eventos[]']").click(function() {
                        parametros = "";
                        pag = 1;
                        cantpag = 1;
                        if ($(this).val() == "**") { //	obtener todos los ELEMENTOS					
                            var $filas = $("input[name='eventos[]']");
                            if ($(this).attr("checked")) {
                                $filas.attr("checked", true);
                            } else {
                                $filas.attr("checked", false);
                            }
                        } else {
                            //	obtener de un ELEMENTO seleccionado
                            if ($(this).attr("checked")) {
                                //	verifico si estan todos los ELEMENTOS seleccionados de los eventos
                                var total_elementos = $(this).closest("div").find("input[name='eventos[]']:gt(0)").length;
                                var elementos_seleccionados = $(this).closest("div").find("input[name='eventos[]']:checked").length;
                                if (elementos_seleccionados == total_elementos) {
                                    var $filas = $(this).closest("div").find("input[name='eventos[]']:first");
                                    $filas.attr("checked", true);
                                }

                            } else {
                                $("input[value='**']").attr("checked", false);
                            }
                        }
                        //	verifico y obtengo los ELEMENTOS seleccionados
                        var total_elementos = $("input[name='eventos[]']:gt(0)").length;
                        var elementos_seleccionados = $("input[name='eventos[]']:gt(0)").filter(":checked").length;
                        if (elementos_seleccionados == total_elementos) {
                            $("input[value='**']").attr("checked", true);
                            parametros = parametros + "&c=*";
                        } else {
                            //	obtengo todos los ELEMENTOS seleccionados de los eventos
                            var $filas = $(this).closest("div").find("input[name='eventos[]']:checked");
                            $filas.each(function() {
                                parametros = parametros + "&e[]=" + $(this).val();
                            })
                        }
                        parametros = parametros + "&te=" + $(this).attr("data-tipo");

                        //console.log(vDatos);
                        if (parametros != "") {
                            parametros = parametros + "&pais=" + $('#codigo_pais').val();
                            parametros = parametros + "&prov=" + $('#provincia').val();
                            parametros = parametros + "&depa=" + $('#departamento').val();
                            parametros = parametros + "&ciud=" + $('#ciudad').val();
                            parametros = parametros + "&tipo=eventos";
                            //	obtengo la LATITUD y LONGITUD del ELEMENTO seleccionado
                            $.getJSON('html/buscarDatos.php?t=elementosBuscar' + parametros, function(data) {
                                borrarMarcadoresEventos();
                                $.each(data, function(key, elemento) {
                                    crearMarcadorEventos(elemento);
                                    //map.panTo(new google.maps.LatLng(elemento.latitud,elemento.longitud));
                                });
                            });

                        } else {
                            borrarMarcadoresEventos();
                        }
                        $(window).resize();
                    });
                };

                if ($("a[name='webmovil[]']").length) { //	marco la publicidad seleccionada
                    $("a[name='webmovil[]']").click(function() {

                        $.get("html/buscarDatos.php?t=ClickWebmovil&id=" + $(this).attr('data-id'));

                        $(".elemento").attr("title", $(this).attr('title'));
                        $(".elemento").colorbox({ iframe: true, href: "elemento.php?id=" + $(this).attr('data-id') + "&lat=" + $("#latitud").val() + "&lng=" + $("#longitud").val(), open: true, rel: "elemento" + $(this).attr('data-id'), width: "90%", height: "90%" });
                    })
                };

                if ($("a[name='recorrido[]']").length) { //	muestro el recorrido al elemento seleccionado
                    $("a[name='recorrido[]']").click(function() {
                        ocultarMarcadoresExcepcional();
                        mostrarMarcadoresExcepcional()
                        borrarMarcadoresEventos();

                        $("#divDirecciones").show();
                        if (!$("#collapseDirecciones").hasClass('in')) {
                            $("#divDirecciones").find('div:first').find('a.accordion-toggle').trigger('click');
                        }
                        $("#accordionDirecciones").empty();
                        $("#accordionDirecciones").append("<p style='text-align: center;'><img src='images/preloader.gif' /> obteniendo datos ...</p>");
                        $(window).resize();

                        var $campo = $(this).closest("label").find("input[name='eventos[]']");
                        if (!$campo.attr("checked")) {
                            $campo.attr("checked", true);
                            $campo.trigger('click');
                            $campo.attr("checked", true);
                        }

                        parametros = "";
                        parametros = parametros + "&e[]=" + $(this).attr('data-id');
                        parametros = parametros + "&te=" + $(this).attr("data-tipo");
                        parametros = parametros + "&pais=" + $('#codigo_pais').val();
                        parametros = parametros + "&prov=" + $('#provincia').val();
                        parametros = parametros + "&depa=" + $('#departamento').val();
                        parametros = parametros + "&ciud=" + $('#ciudad').val();
                        parametros = parametros + "&tipo=eventos";
                        //	obtengo la LATITUD y LONGITUD del ELEMENTO seleccionado
                        $.getJSON('html/buscarDatos.php?t=elementosBuscar' + parametros, function(data) {
                            //borrarMarcadoresEventos();
                            $.each(data, function(key, elemento) {
                                crearMarcadorEventos(elemento);
                                //map.panTo(new google.maps.LatLng(elemento.latitud,elemento.longitud));
                            });
                        });

                        var origen = new google.maps.LatLng($('#latitud').val(), $('#longitud').val());
                        $('#latitudDestino').val($(this).attr('data-lat'));
                        $('#longitudDestino').val($(this).attr('data-lng'));
                        var destino = new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng'));
                        map.panTo(destino);
                        calcularRuta(origen, destino, $("button[name='modorecorrido']").filter(".active").attr('data-mode'));
                    })
                };
            } else {
                $("#accordionEventos").append("<p style='text-align: center;color: #ff0000;'>No hay eventos</p>");
            }
            $(window).resize();
        });
    }

    function obtenerExcepcionales() {
        var parametros = "";
        parametros = parametros + "&c=*";
        parametros = parametros + "&pais=" + $('#codigo_pais').val();
        parametros = parametros + "&prov=" + $('#provincia').val();
        parametros = parametros + "&depa=" + $('#departamento').val();
        parametros = parametros + "&ciud=" + $('#ciudad').val();
        parametros = parametros + "&tipo=excepcional";
        $.getJSON("html/buscarDatos.php?t=elementosBuscar" + parametros, function(data) {
            borrarMarcadoresExcepcional();
            if (data.length) {
                $.each(data, function(key, elemento) {
                    crearMarcadorExcepcional(elemento);
                });
            }
        });
    }
    //obtenerExcepcionales();

    //	lista de categorias
    if ($("#accordionCategorias").length) {
        var listaColoresCategorias = ['#F79646', '#4BACC6', '#8064A2', '#9BBB59', '#C0504D', '#4F81BD', '#1F497D', '#0074CC'];
        $("#accordionCategorias").empty();
        $("#accordionCategorias").append("<label class='checkbox' style='padding-left: 25px;'><input type='checkbox' name='registro[]' value='*' /> Todas las Categorias</label>");
        $.getJSON('html/buscarDatos.php?t=elementosListado', function(data) {
            $.each(data, function(key, elemento) {
                var grupo = $("<div class='accordion-group' style='background: none repeat scroll 0 0 #F5F5F5;'></div>");
                var vID = $("<div>" + elemento.categoria + "</div>");
                vID.html(vID.html().replace(/(^\s*)|(\s*$)|[ ]/g, ""));
                grupo.append($("<div class='accordion-heading'><a class='accordion-toggle btn btn-primary' data-toggle='collapse' data-parent='#accordionCategorias' href='#" + vID.html().toLowerCase() + "' style='padding: 4px 7px;text-decoration: none;text-align: left;background: " + listaColoresCategorias[key] + ";'>" + elemento.categoria + "</a></div>"));
                var cuerpo = $("<div id='" + vID.html().toLowerCase() + "' class='accordion-body collapse'></div>");
                var Itemcategoria = $("<div class='accordion-inner' style='padding: 5px;'></div>");
                Itemcategoria.append("<label class='checkbox' style='clear: both;'><input type='checkbox' name='registro[]' tipo='categoria' value='Categoria:" + elemento.categoria + "' /> <strong>Categoria:</strong> " + elemento.categoria + "</label>");
                $.each(elemento.tipos, function(key2, tipo) {
                    Itemcategoria.append("<label class='checkbox' style='clear: both;'><input type='checkbox' name='registro[]' value='" + tipo.valor + "' /> " + tipo.nombre + "</label>");
                });
                cuerpo.append(Itemcategoria);
                grupo.append(cuerpo);
                $("#accordionCategorias").append(grupo);
            });

            if ($("input[name='registro[]']").length) { //	marco el registro seleccionado
                $("input[name='registro[]']").click(function() {
                    var vQuery = "";
                    if ($(this).val() == "*") { //	obtener todas las categorias de ELEMENTOS					
                        var $filas = $("input[name='registro[]']");
                        if ($(this).attr("checked")) {
                            $filas.attr("checked", true);
                        } else {
                            $filas.attr("checked", false);
                        }
                    } else {
                        var opcion = $(this).val().split(':');
                        if (opcion[0] == "Categoria") { //	obtener de una CATEGORIA seleccionada	
                            //	selecciono todos los ELEMENTOS de la categoria
                            var $filas = $(this).closest("div").find("input[name='registro[]']");
                            if ($(this).attr("checked")) {
                                $filas.attr("checked", true);
                            } else {
                                $("input[value='*']").attr("checked", false);
                                $filas.attr("checked", false);
                            }

                        } else { //	obtener de un ELEMENTO seleccionado
                            if ($(this).attr("checked")) {
                                //	verifico si estan todos los ELEMENTOS seleccionados de la categoria
                                var total_elementos = $(this).closest("div").find("input[name='registro[]']:gt(0)").length;
                                var elementos_seleccionados = $(this).closest("div").find("input[name='registro[]']:checked").length;
                                if (elementos_seleccionados == total_elementos) {
                                    var $filas = $(this).closest("div").find("input[name='registro[]']:first");
                                    $filas.attr("checked", true);
                                }

                            } else {
                                $("input[value='*']").attr("checked", false);
                                //	deshabilito el input de la categoria
                                var $filas = $(this).closest("div").find("input[name='registro[]']:checked");
                                $filas.each(function() {
                                    var opcion = $(this).val().split(':');
                                    if (opcion[0] == "Categoria") {
                                        $(this).attr("checked", false)
                                    }
                                });
                            }
                        }
                    }
                    //	verifico y obtengo si los ELEMENTOS seleccionados
                    var total_elementos = $("input[name='registro[]']:gt(0)").length;
                    var elementos_seleccionados = $("input[name='registro[]']:gt(0)").filter(":checked").length;
                    if (elementos_seleccionados == total_elementos) {
                        $("input[value='*']").attr("checked", true);
                        vQuery = "*";
                    } else {
                        //	obtengo todas las categorias seleccionadas
                        var $filas_categorias = $("input[tipo='categoria']:checkbox");
                        var contador = 0;
                        $filas_categorias.each(function() {
                            var opcion2 = $(this).val().split(':');
                            if ($(this).attr("checked")) {
                                if (contador++ > 0) { vQuery = vQuery + "+"; }
                                vQuery = vQuery + opcion2[1];
                            } else {
                                //	obtengo todos los ELEMENTOS seleccionados de la categoria no seleccionada
                                var $filas = $(this).closest("div").find("input[name='registro[]']:checked");
                                $filas.each(function() {
                                    if (contador++ > 0) { vQuery = vQuery + "+"; }
                                    vQuery = vQuery + $(this).val();
                                })
                            }
                        })
                    }
                    if (vQuery != "") {
                        $("input[name=q]").val(vQuery);
                        $("#btBuscar").trigger('click');
                    } else {
                        $("a#btBorrarResultados").trigger('click');
                        $(window).resize();
                        borrarMarcadoresElementos();

                        vDatos = "";
                        vDatos = vDatos + "&c=*";
                        vDatos = vDatos + "&pais=" + $('#codigo_pais').val();
                        vDatos = vDatos + "&prov=" + $('#provincia').val();
                        vDatos = vDatos + "&depa=" + $('#departamento').val();
                        vDatos = vDatos + "&ciud=" + $('#ciudad').val();

                        obtenerPublicidad(vDatos);
                    }

                });
            };
        });
    }

    //	lista de eventos
    if ($("#accordionEventos").length) {
        obtenerEventos();
    }

    function callback(results, status) {
        $("#accordionResultados").empty();
        if (status == google.maps.places.PlacesServiceStatus.OK) {
            if (results.length) {
                $("#accordionResultados").append("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='resultado[]' value='**' checked='checked' /> Todos los resultados</label>");
                for (var i = 0; i < results.length; i++) {
                    //console.log(results[i]);
                    var nombre = results[i].name.replace('"', '');
                    var nombre = nombre.replace('"', '');
                    var categoria = encontrarCategoria(results[i].types[0]);
                    var elemento = '{ "id" : "' + results[i].id + '", "nombre" : "' + nombre + '" , "tipo" : "' + results[i].types[0] + '", "categoria" : "' + categoria + '", "domicilio" : "' + results[i].vicinity + '", "telefonos" : "", "url" : "", "email" : "", "rating" : "", "latitud" : "' + results[i].geometry.location.lat() + '", "longitud" : "' + results[i].geometry.location.lng() + '", "icono" : "" }';
                    var valores = eval('(' + elemento + ')');
                    crearMarcadorElementos(valores);
                    //createMarker(results[i]);
                    var Item = $("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='resultado[]' value='" + results[i].id + "' checked='checked' />  <a href='javascript:void(0);' data-id='" + results[i].id + "' data-lat='" + results[i].geometry.location.lat() + "' data-lng='" + results[i].geometry.location.lng() + "' title='Mostrar como llegar: " + results[i].name + "' name='recorrido[]'><i class='icon-map-marker icon-white'></i></a> <a href='javascript:void(0);' data-id='" + results[i].id + "' title='WebMóvil: " + results[i].name + "' name='webmovil[]' data-rating='" + results[i].rating + "'><i class='icon-edit icon-white'></i> </a> " + results[i].name + "</label>");
                    $("#accordionResultados").append(Item);
                }

                if ($("a[name='recorrido[]']").length) { //	muestro el recorrido al elemento seleccionado
                    $("a[name='recorrido[]']").click(function() {
                        $("#divDirecciones").show();
                        if (!$("#collapseDirecciones").hasClass('in')) {
                            $("#divDirecciones").find('div:first').find('a.accordion-toggle').trigger('click');
                        }
                        $("#accordionDirecciones").empty();
                        $("#accordionDirecciones").append("<p style='text-align: center;'><img src='images/preloader.gif' /> obteniendo datos ...</p>");
                        $(window).resize();

                        var $campo = $(this).closest("label").find("input[name='resultado[]']");
                        if (!$campo.attr("checked")) {
                            $campo.attr("checked", true);
                            $campo.trigger('click');
                            $campo.attr("checked", true);
                        }
                        var origen = new google.maps.LatLng($('#latitud').val(), $('#longitud').val());
                        $('#latitudDestino').val($(this).attr('data-lat'));
                        $('#longitudDestino').val($(this).attr('data-lng'));
                        var destino = new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng'));
                        map.panTo(destino);
                        calcularRuta(origen, destino, $("button[name='modorecorrido']").filter(".active").attr('data-mode'));

                    })
                };

            } else {
                $("#accordionResultados").append("<p style='text-align: center;color: #ff0000;'>No hay resultados</p>");
            }
        } else {
            $("#accordionResultados").append("<p style='text-align: center;color: #ff0000;'>No hay resultados</p>");
        }
    }

    function createMarker(place) {
        var placeLoc = place.geometry.location;
        var marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location,
            title: place.name
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(place.name);
            infowindow.open(map, this);
        });
    }

    //	resultados de busquedas
    if ($("#btBuscar").length) {
        //	cargo el script de validacion
        $.getScript("xpanel/scripts/h5f/h5f.js", function() {
            H5F.setup($("#formBuscar"));
        });
        $("#btBuscar").click(function(e) {
            //	verifico si esta el formulario completo
            if (document.getElementById("formBuscar").checkValidity() === true) {
                e.preventDefault();
                $("#divResultados").show();
                if (!$("#collapseResultado").hasClass('in')) {
                    $("#divResultados").find('div:first').find('a.accordion-toggle').trigger('click');
                }

                if ($("#accordionResultados").length) {
                    $("#accordionResultados").empty();
                    $("#accordionResultados").append("<p style='text-align: center;'><img src='images/preloader.gif' /> cargando datos ...</p>");
                    vDatos = "";
                    pag = 1;
                    cantpag = 1;
                    vDatos = vDatos + "&c=*";
                    if ($("input[name=q]").val() != "*") {
                        vDatos = vDatos + "&q=" + $("input[name=q]").val();
                    }
                    vDatos = vDatos + "&pais=" + $('#codigo_pais').val();
                    vDatos = vDatos + "&prov=" + $('#provincia').val();
                    vDatos = vDatos + "&depa=" + $('#departamento').val();
                    vDatos = vDatos + "&ciud=" + $('#ciudad').val();
                    //console.log(vDatos);
                    $.getJSON('html/buscarDatos.php?t=elementosBuscar' + vDatos, function(data) {
                        borrarMarcadoresElementos();
                        $("#accordionResultados").empty();
                        if (data.length) {
                            $("#accordionResultados").append("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='resultado[]' value='**' checked='checked' /> Todos los resultados</label>");
                            $.each(data, function(key, elemento) {
                                crearMarcadorElementos(elemento);
                                var Item = $("<label class='checkbox' style='padding-left: 25px;clear: both;'><input type='checkbox' name='resultado[]' value='" + elemento.id + "' checked='checked' />  <a href='javascript:void(0);' data-id='" + elemento.id + "' data-lat='" + elemento.latitud + "' data-lng='" + elemento.longitud + "' title='Mostrar como llegar: " + elemento.nombre + "' name='recorrido[]'><i class='icon-map-marker icon-white'></i></a> <a href='javascript:void(0);' data-id='" + elemento.id + "' title='WebMóvil: " + elemento.nombre + "' name='webmovil[]' data-rating='" + elemento.rating + "'><i class='icon-edit icon-white'></i> </a> " + elemento.nombre + "</label>");
                                $("#accordionResultados").append(Item);
                            });

                            if ($("input[name='resultado[]']").length) { //	marco el registro seleccionado
                                $("input[name='resultado[]']").click(function() {
                                    vDatos = "";
                                    if ($("input[name=q]").val() != "*") {
                                        vDatos = vDatos + "&q=" + $("input[name=q]").val();
                                    }
                                    pag = 1;
                                    cantpag = 1;
                                    if ($(this).val() == "**") { //	obtener todos los ELEMENTOS					
                                        var $filas = $("input[name='resultado[]']");
                                        if ($(this).attr("checked")) {
                                            $filas.attr("checked", true);
                                        } else {
                                            $filas.attr("checked", false);
                                        }
                                    } else {
                                        //	obtener de un ELEMENTO seleccionado
                                        if ($(this).attr("checked")) {
                                            //	verifico si estan todos los ELEMENTOS seleccionados de los resultados
                                            var total_elementos = $(this).closest("div").find("input[name='resultado[]']:gt(0)").length;
                                            var elementos_seleccionados = $(this).closest("div").find("input[name='resultado[]']:checked").length;
                                            if (elementos_seleccionados == total_elementos) {
                                                var $filas = $(this).closest("div").find("input[name='resultado[]']:first");
                                                $filas.attr("checked", true);
                                            }

                                        } else {
                                            $("input[value='**']").attr("checked", false);
                                        }
                                    }
                                    //	verifico y obtengo los ELEMENTOS seleccionados
                                    var total_elementos = $("input[name='resultado[]']:gt(0)").length;
                                    var elementos_seleccionados = $("input[name='resultado[]']:gt(0)").filter(":checked").length;
                                    if (elementos_seleccionados == total_elementos) {
                                        $("input[value='**']").attr("checked", true);
                                        vDatos = vDatos + "&c=*";
                                    } else {
                                        //	obtengo todos los ELEMENTOS seleccionados de los resultados
                                        var $filas = $(this).closest("div").find("input[name='resultado[]']:checked");
                                        $filas.each(function() {
                                            vDatos = vDatos + "&e[]=" + $(this).val();
                                        })
                                    }
                                    //console.log(vDatos);
                                    if (vDatos != "") {
                                        vDatos = vDatos + "&pais=" + $('#codigo_pais').val();
                                        vDatos = vDatos + "&prov=" + $('#provincia').val();
                                        vDatos = vDatos + "&depa=" + $('#departamento').val();
                                        vDatos = vDatos + "&ciud=" + $('#ciudad').val();
                                        //	obtengo la LATITUD y LONGITUD del ELEMENTO seleccionado
                                        $.getJSON('html/buscarDatos.php?t=elementosBuscar' + vDatos, function(data) {
                                            borrarMarcadoresElementos();
                                            $.each(data, function(key, elemento) {
                                                crearMarcadorElementos(elemento);
                                                //map.panTo(new google.maps.LatLng(elemento.latitud,elemento.longitud));
                                            });
                                        });

                                    } else {
                                        borrarMarcadoresElementos();
                                    }
                                    $(window).resize();
                                });
                            };

                            if ($("a[name='webmovil[]']").length) { //	marco la publicidad seleccionada
                                $("a[name='webmovil[]']").click(function() {

                                    $.get("html/buscarDatos.php?t=ClickWebmovil&id=" + $(this).attr('data-id'));

                                    $(".elemento").attr("title", $(this).attr('title'));
                                    $(".elemento").colorbox({ iframe: true, href: "elemento.php?id=" + $(this).attr('data-id') + "&lat=" + $("#latitud").val() + "&lng=" + $("#longitud").val(), open: true, rel: "elemento" + $(this).attr('data-id'), width: "90%", height: "90%" });
                                })
                            };

                            if ($("a[name='recorrido[]']").length) { //	muestro el recorrido al elemento seleccionado
                                $("a[name='recorrido[]']").click(function() {
                                    $("#divDirecciones").show();
                                    if (!$("#collapseDirecciones").hasClass('in')) {
                                        $("#divDirecciones").find('div:first').find('a.accordion-toggle').trigger('click');
                                    }
                                    $("#accordionDirecciones").empty();
                                    $("#accordionDirecciones").append("<p style='text-align: center;'><img src='images/preloader.gif' /> obteniendo datos ...</p>");
                                    $(window).resize();

                                    var $campo = $(this).closest("label").find("input[name='resultado[]']");
                                    if (!$campo.attr("checked")) {
                                        $campo.attr("checked", true);
                                        $campo.trigger('click');
                                        $campo.attr("checked", true);
                                    }
                                    var origen = new google.maps.LatLng($('#latitud').val(), $('#longitud').val());
                                    $('#latitudDestino').val($(this).attr('data-lat'));
                                    $('#longitudDestino').val($(this).attr('data-lng'));
                                    var destino = new google.maps.LatLng($(this).attr('data-lat'), $(this).attr('data-lng'));
                                    map.panTo(destino);
                                    calcularRuta(origen, destino, $("button[name='modorecorrido']").filter(".active").attr('data-mode'));

                                })
                            };
                        } else {
                            //$("#accordionResultados").append("<p style='text-align: center;color: #ff0000;'>No hay resultados</p>");
                            $("#accordionResultados").append("<p style='text-align: center;'><img src='images/preloader.gif' /> buscando datos desde Google Places ...</p>");
                            var tipos = [];
                            if ($("input[name=q]").val() != "*") {
                                var arrayTipos = $("input[name=q]").val().split("+");
                                for (var i = 0; i < arrayTipos.length; i++) {
                                    tipos.push(arrayTipos[i]);
                                }
                                var palabrasclaves = $("input[name=q]").val();
                            }
                            //console.log(tipos);

                            var request = {
                                location: new google.maps.LatLng(document.getElementById("latitud").value, document.getElementById("longitud").value),
                                radius: 5000
                            };
                            if (tipos) {
                                request.types = tipos;
                            }
                            if (palabrasclaves) {
                                request.keyword = [palabrasclaves];
                            }
                            //console.log(request);
                            var service = new google.maps.places.PlacesService(map);
                            service.search(request, callback);
                        }
                        $(window).resize();
                    });
                }
            }
        })

        if (getUrlVars()["q"]) {
            //$("input[name=q]").val(decodeURI(getUrlVars()["q"]));
            $("input[name=q]").val(encodeURIComponent(getUrlVars()["q"]));
            //$("input[name=q]").val(escape(getUrlVars()["q"]));
            $("#btBuscar").trigger('click');
        } else {
            $("input[name=q]").val("");
        }
    }

    if ($("#latitud").length) {
        $("#latitud").keydown(function(event) {
            if (event.keyCode == 13) {
                $("#longitud").focus();
                $("#longitud").select();
            }
        });
        $("#latitud").click(function() {
            $("#latitud").select();
        })
    }

    if ($("#longitud").length) {
        $("#longitud").keydown(function(event) {
            if (event.keyCode == 13) {
                $("#btObtenerDireccion").trigger('click');
            }
        });
        $("#longitud").click(function() {
            $("#longitud").select();
        })
    }

    if ($("#btObtenerDireccion").length) {
        $("#btObtenerDireccion").click(function() {
            var geocoder = new google.maps.Geocoder();
            $("#pais").val("");
            $("#provincia").val("");
            $("#departamento").val("");
            $("#ciudad").val("");
            $("#direccion").val("");

            var latlng = new google.maps.LatLng($("#latitud").val(), $("#longitud").val());
            geocoder.geocode({ 'latLng': latlng }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $.each(results[0].address_components, function(key, componente) {
                            switch (componente.types[0]) {
                                case "country":
                                    $("#codigo_pais").val(componente.short_name);
                                    $("#pais").val(componente.long_name);
                                    break;
                                case "administrative_area_level_1":
                                    switch (componente.long_name) {
                                        case "Departamento de Santa Cruz":
                                            $("#provincia").val("Santa Cruz");
                                            break;
                                        default:
                                            $("#provincia").val(componente.long_name);
                                            break;
                                    }
                                    break;
                                case "administrative_area_level_2":
                                    $("#departamento").val(componente.long_name);
                                    break;
                                case "locality":
                                    switch (componente.long_name) {
                                        case "Yacuíba":
                                            $("#ciudad").val("Yacuiba");
                                            break;
                                        default:
                                            $("#ciudad").val(componente.long_name);
                                            break;
                                    }
                                    break;
                                case "route":
                                    $("#direccion").val(componente.long_name + " " + $("#direccion").val());
                                    break;
                                case "street_number":
                                    $("#direccion").val($("#direccion").val() + " " + componente.long_name);
                                    break;
                                default:
                                    break;
                            }
                        });

                        map.setCenter(latlng);
                        marker.setPosition(latlng);

                        //$("#address").val(results[0].formatted_address);
                        $("#address").val($("#direccion").val() + ", " + $("#ciudad").val() + ", " + $("#departamento").val() + ", " + $("#provincia").val() + ", " + $("#pais").val());
                    } else {
                        alert("Resultado no encontrado");
                    }
                } else {
                    alert("Geocoder fallo: " + status);
                }
            });
        })
        $("#btObtenerDireccion").trigger('click');
    }

    if ($("#address").length) {
        $("#address").keydown(function(event) {
            if (event.keyCode == 13) {
                $("#btObtenerCoordenada").trigger('click');
            }
        });
        $("#address").click(function() {
            $("#address").select();
        })
    }

    if ($("#btObtenerCoordenada").length) {
        $("#btObtenerCoordenada").click(function() {
            var geocoder = new google.maps.Geocoder();
            $("#pais").val("");
            $("#provincia").val("");
            $("#departamento").val("");
            $("#ciudad").val("");
            $("#direccion").val("");

            var address = $("#address").val();
            geocoder.geocode({ 'address': address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $.each(results[0].address_components, function(key, componente) {
                            switch (componente.types[0]) {
                                case "country":
                                    $("#codigo_pais").val(componente.short_name);
                                    $("#pais").val(componente.long_name);
                                    break;
                                case "administrative_area_level_1":
                                    switch (componente.long_name) {
                                        case "Departamento de Santa Cruz":
                                            $("#provincia").val("Santa Cruz");
                                            break;
                                        default:
                                            $("#provincia").val(componente.long_name);
                                            break;
                                    }
                                    break;
                                case "administrative_area_level_2":
                                    $("#departamento").val(componente.long_name);
                                    break;
                                case "locality":
                                    switch (componente.long_name) {
                                        case "Yacuíba":
                                            $("#ciudad").val("Yacuiba");
                                            break;
                                        default:
                                            $("#ciudad").val(componente.long_name);
                                            break;
                                    }
                                    break;
                                case "route":
                                    $("#direccion").val(componente.long_name + " " + $("#direccion").val());
                                    break;
                                case "street_number":
                                    $("#direccion").val($("#direccion").val() + " " + componente.long_name);
                                    break;
                                default:
                                    break;
                            }
                        });

                        if (($("#provincia").val() == "Santa Cruz") && ($("#ciudad").val() == "Santa Cruz")) {
                            $("#latitud").val("-17.783301599527242");
                            $("#longitud").val("-63.18218636512756");

                            map.setCenter(new google.maps.LatLng($("#latitud").val(), $("#longitud").val()));
                            marker.setPosition(new google.maps.LatLng($("#latitud").val(), $("#longitud").val()));
                        } else {
                            map.setCenter(results[0].geometry.location);
                            marker.setPosition(results[0].geometry.location);

                            $("#latitud").val(results[0].geometry.location.lat());
                            $("#longitud").val(results[0].geometry.location.lng());
                        }

                        vDatos = "";
                        vDatos = vDatos + "&c=*";
                        vDatos = vDatos + "&pais=" + $("#codigo_pais").val();
                        vDatos = vDatos + "&prov=" + $("#provincia").val();
                        vDatos = vDatos + "&depa=" + $("#departamento").val();
                        vDatos = vDatos + "&ciud=" + $("#ciudad").val();

                        //	obtengo la publicidad
                        obtenerPublicidad(vDatos);

                        //	obtengo los eventos
                        obtenerEventos();

                    } else {
                        alert("Resultado no encontrado");
                    }

                } else {
                    alert("Geocode no fue satisfactorio por las siguiente razon: " + status);
                }
            });
        })
    }

}