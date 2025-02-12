using System;

namespace CalculadoraConsola
{
    class Program
    {
        static void Main(string[] args)
        {
            bool salir = false;

            while (!salir)
            {
                Console.Clear();
                Console.WriteLine("Bienvenido a la Calculadora");

                // Solicitar y validar el primer número
                double num1 = ObtenerNumero("Ingresa el primer número: ");

                // Solicitar y validar el segundo número
                double num2 = ObtenerNumero("Ingresa el segundo número: ");

                // Solicitar la operación
                Console.Write("Elige la operación (+, -, *, /): ");
                string operacion = Console.ReadLine();

                double resultado = 0;
                bool operacionValida = true;

                switch (operacion)
                {
                    case "+":
                        resultado = num1 + num2;
                        break;
                    case "-":
                        resultado = num1 - num2;
                        break;
                    case "*":
                        resultado = num1 * num2;
                        break;
                    case "/":
                        if (num2 != 0)
                            resultado = num1 / num2;
                        else
                        {
                            Console.WriteLine("Error: División por cero.");
                            operacionValida = false;
                        }
                        break;
                    default:
                        Console.WriteLine("Operación no reconocida.");
                        operacionValida = false;
                        break;
                }

                if (operacionValida)
                    Console.WriteLine($"El resultado es: {resultado}");

                // Preguntar si desea continuar
                Console.WriteLine("\n¿Deseas realizar otra operación? (S/N)");
                string respuesta = Console.ReadLine();
                if (respuesta.Equals("N", StringComparison.OrdinalIgnoreCase))
                {
                    salir = true;
                }
            }
        }

        /// <summary>
        /// Método auxiliar que solicita y valida la entrada de un número.
        /// </summary>
        /// <param name="mensaje">Mensaje a mostrar al usuario</param>
        /// <returns>El número ingresado por el usuario</returns>
        private static double ObtenerNumero(string mensaje)
        {
            double numero;
            bool entradaValida = false;
            do
            {
                Console.Write(mensaje);
                string entrada = Console.ReadLine();
                if (double.TryParse(entrada, out numero))
                {
                    entradaValida = true;
                }
                else
                {
                    Console.WriteLine("Por favor, ingresa un número válido.");
                }
            } while (!entradaValida);
            return numero;
        }
    }
}
