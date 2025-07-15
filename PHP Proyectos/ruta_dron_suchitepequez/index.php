<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Optimizador de Rutas - Suchitepéquez</title>
  <script src="https://d3js.org/d3.v7.min.js"></script>
  <style>
    body { background: #eef6f9; font-family: sans-serif; margin: 0; padding: 20px; }
    #panel { text-align: center; margin-bottom: 20px; }
    button { padding: 10px 20px; background: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #218838; }
    svg { background: #fff; border: 1px solid #ccc; display: block; margin: auto; }
  </style>
</head>
<body>
  <div id="panel">
    <h1>Optimizador de Rutas</h1>
    <button id="runBtn">Ejecutar Algoritmo</button>
    <p>Mejor distancia: <strong id="bestDistance">0.00</strong> km</p>
  </div>

  <svg id="mapCanvas" width="1000" height="600"></svg>
  <svg id="chartCanvas" width="1000" height="250"></svg>

  <div id="infoRuta" style="text-align: center; margin-top: 20px; font-family: sans-serif;">
    <h3>Ruta del Dron:</h3>
    <p id="listaCiudades" style="font-size: 16px; color: #333;"></p>
  </div>

  <script>
    const AJUSTES = {
      poblacionTam: 250,
      generaciones: 500,
      probMutar: 0.12,
      torneo: 4,
      elite: true
    };

    let ciudades = [], nombres = [], distMatriz = [], historialFitness = [];

    const obtenerDistancia = (camino) => {
      let suma = 0;
      for (let i = 0; i < camino.length; i++) {
        const origen = camino[i];
        const destino = camino[(i + 1) % camino.length];
        suma += distMatriz[origen][destino];
      }
      return suma;
    };

    const generarIndividuo = () => {
      const resto = d3.shuffle(d3.range(1, nombres.length));
      return [0, ...resto];
    };

    const cruzar = (a, b) => {
      const punto = Math.floor(Math.random() * (a.length - 2)) + 1;
      const segmento = a.slice(1, punto);
      const resto = b.filter(ciudad => !segmento.includes(ciudad) && ciudad !== 0);
      return [0, ...segmento, ...resto];
    };

    const mutar = (camino) => {
      const nuevo = camino.slice();
      const i = Math.floor(Math.random() * (nuevo.length - 1)) + 1;
      const j = Math.floor(Math.random() * (nuevo.length - 1)) + 1;
      [nuevo[i], nuevo[j]] = [nuevo[j], nuevo[i]];
      return nuevo;
    };

    const seleccionar = (poblacion) => {
      const torneo = d3.shuffle(poblacion).slice(0, AJUSTES.torneo);
      return torneo.reduce((mejor, actual) =>
        obtenerDistancia(actual) < obtenerDistancia(mejor) ? actual : mejor
      );
    };

    const ejecutarGenetico = () => {
      let poblacion = d3.range(AJUSTES.poblacionTam).map(generarIndividuo);
      let mejor = poblacion[0];
      let mejorDist = obtenerDistancia(mejor);

      historialFitness = [];

      for (let gen = 0; gen < AJUSTES.generaciones; gen++) {
        const nuevaGen = AJUSTES.elite ? [mejor] : [];
        while (nuevaGen.length < AJUSTES.poblacionTam) {
          const padreA = seleccionar(poblacion);
          const padreB = seleccionar(poblacion);
          let hijo = cruzar(padreA, padreB);
          if (Math.random() < AJUSTES.probMutar) hijo = mutar(hijo);
          nuevaGen.push(hijo);
        }

        poblacion = nuevaGen;
        const candidato = poblacion.reduce((a, b) =>
          obtenerDistancia(a) < obtenerDistancia(b) ? a : b
        );

        const candDist = obtenerDistancia(candidato);
        if (candDist < mejorDist) {
          mejor = candidato;
          mejorDist = candDist;
        }
        historialFitness.push(mejorDist);
      }

      document.getElementById("bestDistance").textContent = mejorDist.toFixed(2);
      return mejor;
    };

    const visualizarRuta = (ruta) => {
      const svg = d3.select("#mapCanvas");
      svg.selectAll("*").remove();

      // 1. Primero dibuja la imagen de fondo
      svg.append("image")
        .attr("xlink:href", "Municipios-de-Suchitepequez.jpg")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", 1000)
        .attr("height", 600)
        .lower();

      const escalaX = d3.scaleLinear().domain([0, 100]).range([50, 950]);
      const escalaY = d3.scaleLinear().domain([0, 100]).range([550, 50]);

      svg.append("path")
        .datum(ruta)
        .attr("d", d3.line()
          .x(i => escalaX(ciudades[i].x))
          .y(i => escalaY(ciudades[i].y)))
        .attr("fill", "none")
        .attr("stroke", "steelblue")
        .attr("stroke-width", 3)
        .raise();

      svg.selectAll("circle")
        .data(ruta)
        .enter()
        .append("circle")
        .attr("cx", d => escalaX(ciudades[d].x))
        .attr("cy", d => escalaY(ciudades[d].y))
        .attr("r", 8)
        .attr("fill", d => d === 0 ? "#2ecc71" : "#e67e22")
        .attr("stroke", "#000");

      svg.selectAll("text.label")
        .data(ruta)
        .enter()
        .append("text")
        .attr("class", "label")
        .attr("x", d => escalaX(ciudades[d].x + 1.5))
        .attr("y", d => escalaY(ciudades[d].y - 12))
        .style("font-size", "12px")
        .style("font-weight", "bold")
        .style("text-shadow", "1px 1px 2px white");
    };

    const visualizarFitness = () => {
      const svg = d3.select("#chartCanvas");
      svg.selectAll("*").remove();

      const margen = {top: 20, right: 20, bottom: 40, left: 60};
      const ancho = +svg.attr("width") - margen.left - margen.right;
      const alto = +svg.attr("height") - margen.top - margen.bottom;

      const x = d3.scaleLinear().domain([0, historialFitness.length]).range([margen.left, ancho]);
      const y = d3.scaleLinear().domain([d3.min(historialFitness), d3.max(historialFitness)]).range([alto, margen.top]);

      svg.append("path")
        .datum(historialFitness)
        .attr("d", d3.line()
          .x((_, i) => x(i))
          .y(d => y(d)))
        .attr("fill", "none")
        .attr("stroke", "#d9534f")
        .attr("stroke-width", 2);

      svg.append("g")
        .attr("transform", `translate(0,${alto})`)
        .call(d3.axisBottom(x).ticks(6));

      svg.append("g")
        .attr("transform", `translate(${margen.left},0)`)
        .call(d3.axisLeft(y).ticks(6));
    };

    function visualizarAnimacionRuta(ruta) {
      const svg = d3.select("#mapCanvas");
      svg.selectAll("*").remove();

      svg.append("image")
        .attr("xlink:href", "Municipios-de-Suchitepequez.jpg")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", 1000)
        .attr("height", 600)
        .lower();

      const escalaX = d3.scaleLinear().domain([0, 100]).range([50, 950]);
      const escalaY = d3.scaleLinear().domain([0, 100]).range([550, 50]);

      svg.selectAll("circle")
        .data(ruta)
        .enter()
        .append("circle")
        .attr("cx", d => escalaX(ciudades[d].x))
        .attr("cy", d => escalaY(ciudades[d].y))
        .attr("r", 6)
        .attr("fill", d => d === 0 ? "#2ecc71" : "#e67e22")
        .attr("stroke", "#000");

      const nombresOrdenados = ruta.map(i => nombres[i]);
      d3.select("#listaCiudades")
        .text(nombresOrdenados.join(" → "));

      const rutaDibujada = svg.append("path")
        .attr("d", "")
        .attr("fill", "none")
        .attr("stroke", "#c0392b")
        .attr("stroke-width", 2)
        .attr("stroke-dasharray", "5,5"); // línea punteada

      function rutasEnPixeles(rutaArray, escalaX, escalaY) {
        return rutaArray.map(i => [
          escalaX(ciudades[i].x),
          escalaY(ciudades[i].y)
        ]);
      }

      const puntosPixel = rutasEnPixeles(ruta, escalaX, escalaY);
      const puntoInicial = puntosPixel[0];
      const drone = svg.append("image")
        .attr("xlink:href", "drone-svgrepo-com.svg")
        .attr("width", 40)
        .attr("height", 40)

        .attr("x", puntoInicial[0] - 20)
        .attr("y", puntoInicial[1] - 20);

      let lineData = [puntosPixel[0]]; 

      ruta.forEach((ciudadIdx, indice) => {
        if (indice === 0) return; 

        const delay = indice * 1500;

        setTimeout(() => {

          lineData.push(puntosPixel[indice]);

          rutaDibujada
            .attr("d", d3.line()(lineData));

          drone.transition()
            .duration(1000)  // durará 1 seg
            .attr("x", puntosPixel[indice][0] - 20)
            .attr("y", puntosPixel[indice][1] - 20);

          svg.selectAll("circle")
          .filter((_, i) => i === ciudadIdx)
          .transition()
          .duration(300)
          .attr("fill", "#3498db");
        }, delay);
      });
    }

    document.getElementById("runBtn").addEventListener("click", () => {
      const rutaOptima = ejecutarGenetico();
      visualizarAnimacionRuta(rutaOptima);
      visualizarFitness();
    });

    // Cargar datos desde JSON
    fetch("datos.json")
      .then(resp => resp.json())
      .then(datos => {
        nombres = datos.municipios;
        distMatriz = datos.distancias;
        ciudades = datos.coordenadas; // Usar coordenadas fijas del JSON
      })
      .catch(() => alert("Error al cargar datos.json"));
  </script>
</body>
</html>
