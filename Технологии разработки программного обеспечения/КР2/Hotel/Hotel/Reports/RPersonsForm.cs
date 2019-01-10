using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Hotel.Reports
{
    public partial class RPersonsForm : Form
    {
        public RPersonsForm()
        {
            InitializeComponent();
        }

        private void RPersonsForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ReportPersonsCntTableAdapter ta = new HotelDataSetTableAdapters.ReportPersonsCntTableAdapter();
            reportPersonsCntBindingSource.DataSource = ta.GetData(DateTime.Now);
            reportViewer1.RefreshReport();
        }

        private void btnShow_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ReportPersonsCntTableAdapter ta = new HotelDataSetTableAdapters.ReportPersonsCntTableAdapter();
            reportPersonsCntBindingSource.DataSource = ta.GetData(fldDateEnd.Value);
            reportViewer1.RefreshReport();
        }
    }
}
