
const svg = d3.select("#mapa");
const width = +svg.attr("width");
const height = +svg.attr("height");

const projection = d3.geoMercator()
  .center([-91.4, 14.5])
  .scale(16000)
  .translate([width / 2, height / 2]);

const municipiosGeo = [
  { nombre: "Mazatenango", lat: 14.5346, lon: -91.5041 },
  { nombre: "Cuyotenango", lat: 14.6071, lon: -91.4853 },
  { nombre: "San Francisco Zapotitlán", lat: 14.6113, lon: -91.4935 },
  { nombre: "San Bernardino", lat: 14.5850, lon: -91.3955 },
  { nombre: "San José El Idolo", lat: 14.4355, lon: -91.3010 },
  { nombre: "Santo Domingo Suchitepéquez", lat: 14.5110, lon: -91.4088 },
  { nombre: "San Lorenzo", lat: 14.5483, lon: -91.4512 },
  { nombre: "Samayac", lat: 14.5582, lon: -91.3854 },
  { nombre: "San Pablo Jocopilas", lat: 14.5320, lon: -91.3754 },
  { nombre: "San Antonio Suchitepéquez", lat: 14.4450, lon: -91.3142 },
  { nombre: "San Miguel Panán", lat: 14.4175, lon: -91.2854 },
  { nombre: "Chicacao", lat: 14.4692, lon: -91.3302 },
  { nombre: "Patulul", lat: 14.3659, lon: -91.2311 },
  { nombre: "Santa Bárbara", lat: 14.3923, lon: -91.2713 },
  { nombre: "San Juan Bautista", lat: 14.3794, lon: -91.2475 },
  { nombre: "Santo Tomás La Unión", lat: 14.5050, lon: -91.3528 },
  { nombre: "Zunilito", lat: 14.5611, lon: -91.4639 },
  { nombre: "Pueblo Nuevo", lat: 14.6425, lon: -91.5323 },
  { nombre: "Río Bravo", lat: 14.3507, lon: -91.2103 }
];

let nombres = [], distMatriz = [], mejorRuta = [], totalDistancia = 0;

fetch("datos.json")
  .then(res => res.json())
  .then(data => {
    nombres = data.municipios;
    distMatriz = data.distancias;
    mejorRuta = ejecutarGenetico();
    totalDistancia = calcularDistancia(mejorRuta);
    document.getElementById("distancia").textContent = totalDistancia.toFixed(2);
    dibujarRuta(mejorRuta);
  });

function calcularDistancia(ruta) {
  let suma = 0;
  for (let i = 0; i < ruta.length; i++) {
    const a = ruta[i];
    const b = ruta[(i + 1) % ruta.length];
    suma += distMatriz[a][b];
  }
  return suma;
}

function generarIndividuo() {
  const resto = d3.shuffle(d3.range(1, nombres.length));
  return [0, ...resto];
}

function cruzar(a, b) {
  const punto = Math.floor(Math.random() * (a.length - 2)) + 1;
  const segmento = a.slice(1, punto);
  const resto = b.filter(ciudad => !segmento.includes(ciudad) && ciudad !== 0);
  return [0, ...segmento, ...resto];
}

function mutar(ruta) {
  const copia = ruta.slice();
  const i = Math.floor(Math.random() * (ruta.length - 1)) + 1;
  const j = Math.floor(Math.random() * (ruta.length - 1)) + 1;
  [copia[i], copia[j]] = [copia[j], copia[i]];
  return copia;
}

function seleccionar(poblacion) {
  const torneo = d3.shuffle(poblacion).slice(0, 4);
  return torneo.reduce((mejor, actual) =>
    calcularDistancia(actual) < calcularDistancia(mejor) ? actual : mejor
  );
}

function ejecutarGenetico() {
  let poblacion = d3.range(300).map(generarIndividuo);
  let mejor = poblacion[0];
  let mejorDist = calcularDistancia(mejor);

  for (let g = 0; g < 600; g++) {
    const nuevaGen = [mejor];
    while (nuevaGen.length < 300) {
      const a = seleccionar(poblacion);
      const b = seleccionar(poblacion);
      let hijo = cruzar(a, b);
      if (Math.random() < 0.12) hijo = mutar(hijo);
      nuevaGen.push(hijo);
    }
    poblacion = nuevaGen;
    const candidato = poblacion.reduce((a, b) =>
      calcularDistancia(a) < calcularDistancia(b) ? a : b);
    const dist = calcularDistancia(candidato);
    if (dist < mejorDist) {
      mejor = candidato;
      mejorDist = dist;
    }
  }
  return mejor;
}

function dibujarRuta(ruta) {
  svg.selectAll("*").remove();

  const puntos = ruta.map(i => {
    const m = municipiosGeo[i];
    return projection([m.lon, m.lat]);
  });

  svg.append("path")
    .datum(puntos)
    .attr("d", d3.line())
    .attr("fill", "none")
    .attr("stroke", "steelblue")
    .attr("stroke-width", 2);

  svg.selectAll("circle")
    .data(ruta)
    .enter()
    .append("circle")
    .attr("cx", d => projection([municipiosGeo[d].lon, municipiosGeo[d].lat])[0])
    .attr("cy", d => projection([municipiosGeo[d].lon, municipiosGeo[d].lat])[1])
    .attr("r", 5)
    .attr("fill", d => d === 0 ? "green" : "red");

  svg.selectAll("text")
    .data(ruta)
    .enter()
    .append("text")
    .attr("x", d => projection([municipiosGeo[d].lon, municipiosGeo[d].lat])[0] + 5)
    .attr("y", d => projection([municipiosGeo[d].lon, municipiosGeo[d].lat])[1] - 5)
    .text(d => municipiosGeo[d].nombre)
    .attr("font-size", "10px");
}

function iniciar() {
  let index = 0;
  const ruta = mejorRuta.map(i => projection([municipiosGeo[i].lon, municipiosGeo[i].lat]));
  const dron = svg.append("circle").attr("r", 10).attr("fill", "orange");
  function mover() {
    if (index >= ruta.length) return;
    dron.transition()
      .duration(1000)
      .attr("cx", ruta[index][0])
      .attr("cy", ruta[index][1])
      .on("end", () => {
        index++;
        mover();
      });
  }
  mover();
}
