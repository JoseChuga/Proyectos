import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:flutter_image_compress/flutter_image_compress.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter_tts/flutter_tts.dart';
import 'package:path/path.dart' as path;

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Food Recognition App',
      theme: ThemeData(
        primarySwatch: Colors.lightBlue,
        scaffoldBackgroundColor: Colors.lightBlue, // Fondo celeste
      ),
      debugShowCheckedModeBanner: false,
      home: const HomeScreen(),
    );
  }
}

class HomeScreen extends StatefulWidget {
  const HomeScreen({Key? key}) : super(key: key);

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  File? _image;
  bool _loading = false;
  String _statusMessage = '';

  final ImagePicker _picker = ImagePicker();
  final FlutterTts _flutterTts = FlutterTts();

  List<DetalleAlimento> _detalles = [];

  @override
  void dispose() {
    _flutterTts.stop();
    super.dispose();
  }

  Future<void> _pickImage(ImageSource source) async {
    final pickedFile = await _picker.pickImage(source: source);
    if (pickedFile == null) return;

    final original = File(pickedFile.path);
    final compressed = await _compressAndResize(original) ?? original;

    setState(() {
      _image = compressed;
      _detalles.clear();
      _statusMessage = '';
      _loading = true;
    });

    await _sendImageForRecognition(compressed);
  }

  Future<File?> _compressAndResize(File file) async {
    try {
      final dir = path.dirname(file.path);
      final name = path.basenameWithoutExtension(file.path);
      final ext = path.extension(file.path);
      final targetPath = path.join(dir, '${name}_512x512$ext');

      final result = await FlutterImageCompress.compressAndGetFile(
        file.path,
        targetPath,
        quality: 100,
        minWidth: 512,
        minHeight: 512,
      );
      return result == null ? null : File(result.path);
    } catch (e) {
      debugPrint('Compression error: $e');
      return null;
    }
  }

  Future<void> _sendImageForRecognition(File imageFile) async {
    try {
      final uri = Uri.parse('http://192.168.1.102:8000/analizar/');
      final request = http.MultipartRequest('POST', uri)
        ..files.add(await http.MultipartFile.fromPath('file', imageFile.path));

      final streamedResponse =
          await request.send().timeout(const Duration(seconds: 120));
      final responseBody = await streamedResponse.stream.bytesToString();

      if (streamedResponse.statusCode == 200) {
        final data = json.decode(responseBody) as Map<String, dynamic>;
        setState(() {
          _detalles = (data['detalles'] as List<dynamic>)
              .map((e) => DetalleAlimento.fromJson(e as Map<String, dynamic>))
              .toList();
          _statusMessage = 'Reconocimiento completado.';
        });
        await _speakAllContent();
      } else {
        setState(() =>
            _statusMessage = 'Error API: ${streamedResponse.statusCode}');
      }
    } on SocketException {
      setState(() => _statusMessage = 'No se pudo conectar al servidor.');
    } on TimeoutException {
      setState(() => _statusMessage = 'Tiempo de espera agotado.');
    } catch (e) {
      setState(() => _statusMessage = 'Error inesperado: $e');
    } finally {
      setState(() => _loading = false);
    }
  }

  Future<void> _speakAllContent() async {
    final buffer = StringBuffer()
      ..write('Se detectaron ${_detalles.length} alimentos. ');
    for (var item in _detalles) {
      buffer
        ..write('Alimento: ${item.nombreLegible}. ')
        ..write('Calorías: ${item.calorias} kilocalorías. ')
        ..write('Proteínas: ${item.proteinas} gramos. ')
        ..write('Grasas: ${item.grasas} gramos. ')
        ..write('Carbohidratos: ${item.carbohidratos} gramos. ')
        ..write('Fibra: ${item.fibra} gramos. ')
        ..write('Azúcares: ${item.azucares} gramos. ')
        ..write('Sodio: ${item.sodio} miligramos. ')
        ..write('Colesterol: ${item.colesterol} miligramos. ');
    }
    await _flutterTts.speak(buffer.toString());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Reconocimiento de Alimentos'),
        centerTitle: true,
      ),
      body: Column(
        children: [
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              ElevatedButton.icon(
                onPressed: () => _pickImage(ImageSource.camera),
                icon: const Icon(Icons.camera_alt),
                label: const Text('Cámara'),
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(30),
                  ),
                ), // Botón redondo
              ),
              ElevatedButton.icon(
                onPressed: () => _pickImage(ImageSource.gallery),
                icon: const Icon(Icons.photo),
                label: const Text('Galería'),
                style: ElevatedButton.styleFrom(
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(30),
                  ),
                ), // Botón redondo
              ),
            ],
          ),
          const SizedBox(height: 16),
          if (_image != null)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(12),
                child: Image.file(
                  _image!,
                  height: 200,
                  fit: BoxFit.cover,
                ).animate().fade(duration: 600.ms),
              ),
            ),
          const SizedBox(height: 16),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : ListView.builder(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    itemCount: _detalles.length,
                    itemBuilder: (_, i) {
                      final item = _detalles[i];
                      return Card(
                        color: Colors.yellow.shade100,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        margin: const EdgeInsets.symmetric(vertical: 6),
                        child: Padding(
                          padding: const EdgeInsets.all(12),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                '🍽️ ${item.nombreLegible}',
                                style: const TextStyle(
                                    fontSize: 18, fontWeight: FontWeight.bold),
                              ),
                              const SizedBox(height: 4),
                              Text('Calorías: ${item.calorias} kcal'),
                              Text('Proteínas: ${item.proteinas} g'),
                              Text('Grasas: ${item.grasas} g'),
                              Text('Carbohidratos: ${item.carbohidratos} g'),
                              Text('Fibra: ${item.fibra} g'),
                              Text('Azúcares: ${item.azucares} g'),
                              Text('Sodio: ${item.sodio} mg'),
                              Text('Colesterol: ${item.colesterol} mg'),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
          ),
          if (_statusMessage.isNotEmpty)
            Padding(
              padding: const EdgeInsets.all(16),
              child: Text(
                _statusMessage,
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
            ),
        ],
      ),
    );
  }
}

class DetalleAlimento {
  final String nombreLegible;
  final int calorias;
  final double proteinas;
  final double grasas;
  final double carbohidratos;
  final double fibra;
  final double azucares;
  final double sodio;
  final double colesterol;
  final List<String> riesgos;
  final List<String> restricciones;
  final List<String> recomendaciones;

  DetalleAlimento({
    required this.nombreLegible,
    required this.calorias,
    required this.proteinas,
    required this.grasas,
    required this.carbohidratos,
    required this.fibra,
    required this.azucares,
    required this.sodio,
    required this.colesterol,
    required this.riesgos,
    required this.restricciones,
    required this.recomendaciones,
  });

  factory DetalleAlimento.fromJson(Map<String, dynamic> json) {
    final info = json['informacion_nutricional'] as Map<String, dynamic>?;
    return DetalleAlimento(
      nombreLegible: json['alimento'] as String? ?? '',
      calorias: (info?['calorias'] as num?)?.toInt() ?? 0,
      proteinas: (info?['proteinas'] as num?)?.toDouble() ?? 0,
      grasas: (info?['grasas'] as num?)?.toDouble() ?? 0,
      carbohidratos: (info?['carbohidratos'] as num?)?.toDouble() ?? 0,
      fibra: (info?['fibra'] as num?)?.toDouble() ?? 0,
      azucares: (info?['azucares'] as num?)?.toDouble() ?? 0,
      sodio: (info?['sodio'] as num?)?.toDouble() ?? 0,
      colesterol: (info?['colesterol'] as num?)?.toDouble() ?? 0,
      riesgos: List<String>.from(json['riesgos'] as List<dynamic>? ?? []),
      restricciones: List<String>.from(json['restricciones'] as List<dynamic>? ?? []),
      recomendaciones: List<String>.from(json['recomendaciones'] as List<dynamic>? ?? []),
    );
  }
}
