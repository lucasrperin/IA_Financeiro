const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql2/promise');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(bodyParser.json());
app.use(express.static(path.join(__dirname, 'public')));

// Configurar conexão MySQL
const dbConfig = {
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'controle_financeiro'
};

// Inserir nova despesa
app.post('/despesas', async (req, res) => {
  try {
    const { valor, categoria, data, descricao } = req.body;
    const conn = await mysql.createConnection(dbConfig);
    await conn.execute(
      'INSERT INTO despesas (valor, categoria, data, descricao) VALUES (?, ?, ?, ?)',
      [valor, categoria, data, descricao]
    );
    await conn.end();
    res.json({ success: true });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Erro ao inserir despesa' });
  }
});

// Agregados para análise
app.get('/analise', async (req, res) => {
  try {
    const conn = await mysql.createConnection(dbConfig);

    // Totais por categoria
    const [cats] = await conn.execute(
      'SELECT categoria, SUM(valor) AS total FROM despesas GROUP BY categoria'
    );

    // Totais por mês
    const [meses] = await conn.execute(
      `SELECT DATE_FORMAT(data, '%Y-%m') AS mes, SUM(valor) AS total
       FROM despesas
       GROUP BY mes ORDER BY mes`
    );

    await conn.end();
    res.json({ totCategoria: cats, totMes: meses });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Erro ao buscar análise' });
  }
});

app.listen(PORT, () => console.log(`Servidor rodando em http://localhost:${PORT}`));