import hashlib

# Solicitar la contraseña al usuario
password = input("Ingresa la contraseña que deseas encriptar en SHA-512: ")

# Generar el hash SHA-512
hashed_password = hashlib.sha512(password.encode()).hexdigest()

# Mostrar el hash en la terminal
print(f"El hash SHA-512 de la contraseña es: {hashed_password}")