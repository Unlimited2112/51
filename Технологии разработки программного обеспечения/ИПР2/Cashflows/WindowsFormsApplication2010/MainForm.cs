using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.Entity;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using WindowsFormsApplication.Model;

namespace WindowsFormsApplication
{
    public partial class MainForm : Form
    {
        CashflowsContext db;
        public MainForm()
        {
            InitializeComponent();
            db = new CashflowsContext();
            db.Cashflows.Load();
            dgvCashflows.DataSource = db.Cashflows.Local.ToBindingList();
        }

        private void mmNew_Click(object sender, EventArgs e)
        {
            CashflowsForm frm = new CashflowsForm();
            List<Direction> directions = db.Directions.ToList();
            frm.fldDirection.DataSource = directions;
            frm.fldDirection.ValueMember = "Id";
            frm.fldDirection.DisplayMember = "Name";

            DialogResult result = frm.ShowDialog(this);

            if (result == DialogResult.OK)
            {
                Cashflow cashflow = new Cashflow();
                cashflow.Description = frm.fldDescription.Text;
                cashflow.Summa = (double)frm.fldSumma.Value;
                cashflow.Direction = (Direction)frm.fldDirection.SelectedItem;
                db.Cashflows.Add(cashflow);
                db.SaveChanges();
                dgvCashflows.Refresh();
            }
            else
                return;
        }

        private void mmEdit_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvCashflows["idTextBoxColumn", dgvCashflows.CurrentRow.Index].Value;
            Cashflow cashflow = db.Cashflows.Find(itemId);

            CashflowsForm frm = new CashflowsForm();
            List<Direction> directions = db.Directions.ToList();
            frm.fldDirection.DataSource = directions;
            frm.fldDirection.ValueMember = "Id";
            frm.fldDirection.DisplayMember = "Name";
            frm.fldDescription.Text = cashflow.Description;
            frm.fldSumma.Value = (decimal)cashflow.Summa;
            frm.fldDirection.SelectedItem = cashflow.Direction;

            DialogResult result = frm.ShowDialog(this);

            if (result == DialogResult.OK)
            {
                cashflow.Description = frm.fldDescription.Text;
                cashflow.Summa = (double)frm.fldSumma.Value;
                cashflow.Direction = (Direction)frm.fldDirection.SelectedItem;
                db.Entry(cashflow).State = EntityState.Modified;
                db.SaveChanges();
                dgvCashflows.Refresh();
            }
            else
                return;
        }


        private void mmDelete_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvCashflows["idTextBoxColumn", dgvCashflows.CurrentRow.Index].Value;
            if (MessageBox.Show(
                    "Вы действительно хотите удалить ДДС?",
                    "Подтвердите удаление",
                    MessageBoxButtons.OKCancel,
                    MessageBoxIcon.Question
                ) == DialogResult.OK)
            {
                Cashflow cashflow = db.Cashflows.Find(itemId);
                db.Cashflows.Remove(cashflow);
                db.SaveChanges();
                dgvCashflows.Refresh();
            }
        }
        
    }
}
