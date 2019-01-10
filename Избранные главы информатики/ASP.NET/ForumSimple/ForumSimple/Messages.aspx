<%@ Page Title="" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Messages.aspx.cs" Inherits="ForumSimple.Messages" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <h3><asp:HyperLink ID="linkRubric" runat="server" NavigateUrl="" Text="" Visible="false" /><asp:Literal ID="literalTopicName" runat="server" /></h3>
    <asp:Label ID="hiddenTopicID" runat="server" Text="0" Visible="false" />
    <asp:Label ID="hiddenUserID" runat="server" Text="0" Visible="false" />
    <asp:ListView ID="ListViewMessages" runat="server" DataKeyNames="id" 
        DataSourceID="dsMessages" InsertItemPosition="LastItem">
        <EditItemTemplate>
            <tr runat="server">
                <td colspan="2" class="col-xs-11">
                    <div class="form-group">
                        <label for="messageTextBox">Изменить сообщение</label>
                        <asp:TextBox ID="messageTextBox" runat="server" Text='<%# Bind("message") %>' CssClass="form-control" Rows="5" TextMode="MultiLine" ValidationGroup="UpdateValidationGroup" />
                        <span class="help-block">
                            <asp:RequiredFieldValidator runat="server" ID="RequiredFieldValidator2" ControlToValidate="messageTextBox" ValidationGroup="UpdateValidationGroup" Display="Dynamic">Сообщение не может быть пустым</asp:RequiredFieldValidator>
                        </span>
                    </div>
                    <div class="checkbox">
                    <div runat="server" class="checkbox" visible='<%# AllowDisabled() %>'>
                        <label>
                            <asp:CheckBox ID="CheckBox1" runat="server" Checked='<%# Bind("disabled") %>' /> Скрыть сообщение
                        </label>
                    </div>
                </td>
                <td class="col-xs-1">
                    <asp:LinkButton ID="UpdateButton" runat="server" CommandName="Update" CssClass="btn btn-default btn-sm" ValidationGroup="UpdateValidationGroup"><span class="glyphicon glyphicon-ok"></span></asp:LinkButton>
                    <asp:LinkButton ID="CancelButton" runat="server" CommandName="Cancel" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></asp:LinkButton>
                </td>
            </tr>
        </EditItemTemplate>
        <EmptyDataTemplate>
            Нет данных...
        </EmptyDataTemplate>
        <InsertItemTemplate>
            <tr runat="server" visible='<%# AllowInsert() %>'>
                <td colspan="2" class="col-xs-11">
                    <div class="form-group">
                        <label for="messageTextBox">Добавить сообщение</label>
                        <asp:TextBox ID="messageTextBox" runat="server" Text='<%# Bind("message") %>' CssClass="form-control" Rows="5" TextMode="MultiLine" ValidationGroup="InsertValidationGroup" />
                        <span class="help-block">
                            <asp:RequiredFieldValidator runat="server" ID="RequiredFieldValidator2" ControlToValidate="messageTextBox" ValidationGroup="InsertValidationGroup" Display="Dynamic">Сообщение не может быть пустым</asp:RequiredFieldValidator>
                        </span>
                    </div>
                </td>
                <td class="col-xs-1">
                    <asp:LinkButton ID="InsertButton" runat="server" CommandName="Insert" CssClass="btn btn-default btn-sm" ValidationGroup="InsertValidationGroup"><span class="glyphicon glyphicon-ok"></span></asp:LinkButton>
                    <asp:LinkButton ID="CancelButton" runat="server" CommandName="Cancel" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></asp:LinkButton>
                </td>
            </tr>
        </InsertItemTemplate>
        <ItemTemplate>
            <tr>
                <td class="col-xs-2">
                    <asp:HyperLink ID="linkUser" runat="server" Text='<%# Eval("login") %>' NavigateUrl='<%# "~/Profile.aspx?UserID=" + Eval("users") %>' />
                    <br />
                    <small><asp:Label ID="datesLabel" runat="server" Text='<%# Eval("dates") %>' /></small>
                </td>
                <td class="col-xs-9">
                    <small><asp:Label ID="messageLabel" runat="server" Text='<%# GetMessage(Eval("message"), Eval("disabled")) %>' /></small>
                </td>
                <td class="col-xs-1">
                    <div runat="server" visible='<%# AllowEdit(Eval("login"), Eval("disabled")) %>'>
                        <asp:LinkButton ID="EditButton" runat="server" CommandName="Edit" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></asp:LinkButton>
                        <asp:LinkButton ID="DeleteButton" runat="server" CommandName="Delete" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove"></span></asp:LinkButton>
                    </div>
                </td>
            </tr>
        </ItemTemplate>
        <LayoutTemplate>
            <table ID="itemPlaceholderContainer" runat="server" class="table table-striped">
                <tbody>
                    <tr ID="itemPlaceholder" runat="server">
                    </tr>
                </tbody>
            </table>
            <asp:DataPager ID="DataPager1" runat="server">
                <Fields>
                    <asp:NextPreviousPagerField ButtonType="Button" ShowFirstPageButton="True" ShowNextPageButton="False" ShowPreviousPageButton="False" ButtonCssClass="btn btn-default btn-sm" />
                    <asp:NumericPagerField />
                    <asp:NextPreviousPagerField ButtonType="Button" ShowLastPageButton="True" ShowNextPageButton="False" ShowPreviousPageButton="False" ButtonCssClass="btn btn-default btn-sm" />
                </Fields>
            </asp:DataPager>
        </LayoutTemplate>
    </asp:ListView>
    <asp:ObjectDataSource ID="dsMessages" runat="server" DeleteMethod="DeleteQuery" 
        InsertMethod="InsertQuery" OldValuesParameterFormatString="original_{0}" 
        SelectMethod="GetDataByTopics" 
        TypeName="ForumSimple.ForumDataSetTableAdapters.messageViewTableAdapter" 
        UpdateMethod="UpdateQuery">
        <DeleteParameters>
            <asp:Parameter Name="Original_id" Type="Int32" />
        </DeleteParameters>
        <InsertParameters>
            <asp:Parameter Name="message" Type="String" />
            <asp:ControlParameter ControlID="hiddenTopicID" DefaultValue="0" 
                Name="topics" PropertyName="Text" Type="Int32" />
            <asp:ControlParameter ControlID="hiddenUserID" DefaultValue="0" 
                Name="users" PropertyName="Text" Type="Int32" />
        </InsertParameters>
        <SelectParameters>
            <asp:ControlParameter ControlID="hiddenTopicID" DefaultValue="0" Name="topics" 
                PropertyName="Text" Type="Int32" />
        </SelectParameters>
        <UpdateParameters>
            <asp:Parameter Name="message" Type="String" />
            <asp:Parameter Name="disabled" Type="Boolean" />
            <asp:Parameter Name="Original_id" Type="Int32" />
        </UpdateParameters>
    </asp:ObjectDataSource>
</asp:Content>
