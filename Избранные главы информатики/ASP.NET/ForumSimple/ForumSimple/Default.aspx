<%@ Page Title="Мой форум - Разделы" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="ForumSimple.Default" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <asp:ListView ID="ListViewRubrics" runat="server" DataKeyNames="id" 
        DataSourceID="dsRubrics" InsertItemPosition="LastItem">
        <EditItemTemplate>
            <div class="row rubric-list">
                <div class="col-xs-10">
                    <asp:TextBox ID="nameTextBox" runat="server" Text='<%# Bind("name") %>' CssClass="form-control" ValidationGroup="EditValidationGroup" />
                </div>
                <div class="col-xs-2 text-right">
                    <asp:LinkButton ID="UpdateButton" runat="server" CommandName="Update" CssClass="btn btn-default btn-sm" ValidationGroup="EditValidationGroup"><span class="glyphicon glyphicon-ok"></span></asp:LinkButton>
                    <asp:LinkButton ID="CancelButton" runat="server" CommandName="Cancel" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></asp:LinkButton>
                </div>
                <div class="col-xs-12">
                    <asp:RequiredFieldValidator ID="reqName" runat="server" ControlToValidate="nameTextBox" Display="Dynamic" ErrorMessage="Наименование не может быть пустым" ValidationGroup="EditValidationGroup" />
                </div>
            </div>
        </EditItemTemplate>
        <EmptyDataTemplate>
            <span>Нет разделов...</span>
        </EmptyDataTemplate>
        <InsertItemTemplate>
            <div runat="server" class="row rubric-list" visible='<%# AllowInsert() %>'>
                <div class="col-xs-12">
                    <asp:Label ID="nameInsLabel" runat="server" Text="Добавить раздел" />
                </div>
                <div class="col-xs-10">
                    <asp:TextBox ID="nameTextBox" runat="server" Text='<%# Bind("name") %>' CssClass="form-control" ValidationGroup="InsertValidationGroup" />
                </div>
                <div class="col-xs-2 text-right">
                    <asp:LinkButton ID="InsertButton" runat="server" CommandName="Insert" CssClass="btn btn-default btn-sm" ValidationGroup="InsertValidationGroup"><span class="glyphicon glyphicon-ok"></span></asp:LinkButton>
                    <asp:LinkButton ID="CancelButton" runat="server" CommandName="Cancel" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></asp:LinkButton>
                </div>
                <div class="col-xs-12">
                    <asp:RequiredFieldValidator ID="reqName" runat="server" ControlToValidate="nameTextBox" Display="Dynamic" ErrorMessage="Наименование не может быть пустым" ValidationGroup="InsertValidationGroup" />
                </div>
            </div>
        </InsertItemTemplate>
        <ItemTemplate>
            <div class="row rubric-list">
                <div class="col-xs-11">
                   <asp:HyperLink ID="nameHyperLink" runat="server" Text='<%# Eval("name") %>' NavigateUrl='<%# "~/Topics.aspx?RubricID=" + Eval("id") %>' />
                </div>
                <div class="col-xs-1 text-right">
                    <asp:LoginView ID="menuLeftLoginView" runat="server">
                        <RoleGroups>
                            <asp:RoleGroup Roles="Admin">
                                <ContentTemplate>
                                    <asp:LinkButton ID="EditButton" runat="server" CommandName="Edit" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></asp:LinkButton>
                                </ContentTemplate>
                            </asp:RoleGroup>
                        </RoleGroups>
                    </asp:LoginView>
                </div>
            </div>
        </ItemTemplate>
        <LayoutTemplate>
            <div ID="itemPlaceholderContainer" runat="server">
                <span runat="server" id="itemPlaceholder" />
            </div>
        </LayoutTemplate>
    </asp:ListView>
    <asp:ObjectDataSource ID="dsRubrics" runat="server" InsertMethod="Insert" 
        OldValuesParameterFormatString="original_{0}" SelectMethod="GetData" 
        TypeName="ForumSimple.ForumDataSetTableAdapters.rubricsTableAdapter" 
        UpdateMethod="Update">
        <InsertParameters>
            <asp:Parameter Name="name" Type="String" />
        </InsertParameters>
        <UpdateParameters>
            <asp:Parameter Name="name" Type="String" />
            <asp:Parameter Name="Original_id" Type="Int32" />
        </UpdateParameters>
    </asp:ObjectDataSource>
</asp:Content>
