import mysql.connector
import os
from pathlib import Path
from dotenv import load_dotenv

caminho_env = Path(__file__).resolve().parent.parent / ".env"

load_dotenv(caminho_env)
def conexao_banco():
    conexao = mysql.connector.connect(
        host = os.getenv("MYSQL_HOST"),
        port =os.getenv("MYSQL_PORT"),
        user = os.getenv("MYSQL_USER"),
        password = os.getenv("MYSQL_PASSWORD"),
        database = os.getenv("MYSQL_DATABASE"),
    )
    return conexao
