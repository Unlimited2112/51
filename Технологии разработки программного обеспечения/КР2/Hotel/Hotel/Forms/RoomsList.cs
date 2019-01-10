using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Hotel.Forms
{
    public partial class RoomsList : Form
    {
        public RoomsList()
        {
            InitializeComponent();
        }

        private void RoomsList_Load(object sender, EventArgs e)
        {
            RefreshTable();
        }

        private void RefreshTable()
        {
            HotelDataSetTableAdapters.RoomsViewTableAdapter ta = new HotelDataSetTableAdapters.RoomsViewTableAdapter();
            tableBindingSource.DataSource = ta.GetData();
        }

        private void menuNew_Click(object sender, EventArgs e)
        {
            RoomForm frm = new RoomForm(-1);
            frm.ShowDialog();
            RefreshTable();
        }

        private void menuEdit_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvMain["idDataGridViewTextBoxColumn", dgvMain.CurrentRow.Index].Value;
            RoomForm frm = new RoomForm(itemId);
            frm.ShowDialog();
            RefreshTable();
        }

        private void menuDelete_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvMain["idDataGridViewTextBoxColumn", dgvMain.CurrentRow.Index].Value;
            if (MessageBox.Show(
                    "Вы действительно хотите удалить элемент?",
                    "Подтверждение удаления",
                    MessageBoxButtons.YesNo,
                    MessageBoxIcon.Warning,
                    MessageBoxDefaultButton.Button2
                ) == DialogResult.Yes)
            {
                HotelDataSetTableAdapters.ScalarQueriesTableAdapter taScalar = new HotelDataSetTableAdapters.ScalarQueriesTableAdapter();
                if ((int)taScalar.CntMovesByRoom(itemId) > 0)
                    MessageBox.Show(
                        "Нельзя удалить элемент, на который имеются ссылки!",
                        "Внимание!",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Error
                    );
                else
                {
                    HotelDataSetTableAdapters.RoomsViewTableAdapter ta = new HotelDataSetTableAdapters.RoomsViewTableAdapter();
                    ta.DeleteQuery(itemId);
                    RefreshTable();
                }
            }
        }
    }
}
